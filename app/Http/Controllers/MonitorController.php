<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Models\ShodanHost;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class MonitorController extends Controller
{
    protected $client;
    protected $shodanApiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->shodanApiKey = env('SHODAN_API_KEY');  // Ambil API Key dari .env
    }

    // Method untuk menampilkan dashboard
    public function index()
    {
        // Ambil semua data ShodanHost dari database
        $hosts = ShodanHost::all();

        $shodanHosts = ShodanHost::select('ip', 'hostnames', 'ports', 'vulns','country')->get();
        $locations = ShodanHost::select('latitude', 'longitude', 'ip')->get();
        // Kirim data ke view 'dashboard'

        $totalPorts = ShodanHost::distinct('ports')->count('ports');
        $totalVulns = ShodanHost::whereNotNull('vulns')->count(); // misalnya field 'vulnerability' berisi data vuln
        $totalHosts = ShodanHost::distinct('ip')->count('ip'); // jika ip unik

        return view('dashboard', compact('hosts', 'locations', 'shodanHosts', 'totalPorts', 'totalVulns', 'totalHosts'));
    }

    public function showResult($id)
    {
        // Ambil data host berdasarkan ID
        $host = ShodanHost::findOrFail($id);

        // Kirim data ke view 'result'
        return view('result', compact('host'));
    }

    public function destroy($id)
    {
        $host = ShodanHost::find($id);
        if (!$host) {
            return response()->json(['message' => 'Host not found'], 404);
        }

        $host->delete();

        return redirect()->back()->with('success', 'Host deleted successfully.');
    }

    public function scan(Request $request)
    {
        $ip = $request->input('ip');
        $email = $request->input('email'); // Ambil email dari form input
        $data = [];

        try {
            // Mengambil informasi host dari Shodan
            $response = $this->client->get("https://api.shodan.io/shodan/host/$ip?key={$this->shodanApiKey}");
            $result = json_decode($response->getBody(), true);

            // Ambil banner services
            $serviceBanners = [];
            if (isset($result['data'])) {
                foreach ($result['data'] as $service) {
                    $serviceBanners[] = [
                        'port' => $service['port'],
                        'banner' => $service['data'] ?? 'No banner found',
                    ];
                }
            }

            // Menyimpan data hasil scan ke dalam database
            $host = ShodanHost::create([
                'ip' => $result['ip_str'] ?? 'N/A',
                'email' => $email,
                'hostnames' => json_encode($result['hostnames'] ?? []),
                'country' => $result['country_name'] ?? 'N/A',
                'city' => $result['city'] ?? 'N/A',
                'ports' => json_encode($result['ports'] ?? []),
                'vulns' => json_encode($result['vulns'] ?? []),
                'isp' => $result['isp'] ?? 'N/A',
                'domains' => json_encode($result['domains'] ?? []),
                'organizations' => json_encode($result['organizations'] ?? []),
                'asn' => $result['asn'] ?? 'N/A',
                'latitude' => $result['latitude'] ?? null,
                'longitude' => $result['longitude'] ?? null,
                'service_banners' => json_encode($serviceBanners),
            ]);

            // Mengambil deskripsi CVE dari Circle.LU API
            $cveDetails = [];
            if (isset($result['vulns']) && is_array($result['vulns'])) {
                foreach ($result['vulns'] as $cve) {
                    // Validasi format CVE
                    if (preg_match('/^CVE-\d{4}-\d{4,}$/', $cve)) {
                        try {
                            $cveResponse = $this->client->get("https://cve.circl.lu/api/cve/$cve");
                            $cveData = json_decode($cveResponse->getBody(), true);

                            $cveDetails[$cve] = $cveData['containers']['cna']['descriptions'][0]['value']
                                                ?? 'Tidak ada deskripsi';
                        } catch (\Exception $e) {
                            $cveDetails[$cve] = 'Gagal mengambil data CVE: ' . $e->getMessage();
                        }
                    } else {
                        $cveDetails[$cve] = 'Format CVE tidak valid';
                    }
                }
            }

            // Menyimpan deskripsi CVE ke dalam database
            $host->cve_details = json_encode($cveDetails);
            $host->save();

            return redirect()->route('monitor.index')->with('success', 'IP scanned and data saved successfully!');
        } catch (\Exception $e) {
            return redirect()->route('monitor.index')->withErrors('Failed to scan IP: ' . $e->getMessage());
        }
    }

    // Fungsi untuk menampilkan PDF
    public function exportPdf($id)
    {
        // Ambil data host berdasarkan ID
        $host = ShodanHost::findOrFail($id);

        // Menghasilkan PDF menggunakan view yang sudah ada
        $pdf = Pdf::loadView('pdf', compact('host'));

        // Menyajikan PDF sebagai download
        return $pdf->download("scan_result_{$host->ip}.pdf");
    }

    public function sendPortNewEmail($email, $port)
    {
        try {
            // Kirim email menggunakan Mailable
            Mail::to($email)->send(new NewPortNotification($port));
            \Log::info("Email notification sent to {$email} for port {$port->port_number}");
        } catch (\Exception $e) {
            \Log::error("Failed to send email to {$email}: " . $e->getMessage());
        }
    }

    public function handleNewPortData(Request $request)
    {
        // Mendapatkan IP dan email dari input
        $ip = $request->input('ip');
        $email = $request->input('email');

        // Menggunakan Client untuk memanggil API Shodan
        try {
            $response = $this->client->get("https://api.shodan.io/shodan/host/{$ip}?key={$this->shodanApiKey}");
            $shodanData = json_decode($response->getBody(), true);

            if ($shodanData && isset($shodanData['ports'])) {
                $serviceData = $shodanData['ports']; // Ambil data port

                // Mencari atau membuat host berdasarkan IP
                $host = ShodanHost::firstOrCreate(
                    ['ip' => $ip],
                    ['email' => $email]
                );

                // Menyimpan port baru jika belum ada
                foreach ($serviceData as $portNumber) {
                    $existingPort = Port::where('shodan_host_id', $host->id)
                                       ->where('port_number', $portNumber)
                                       ->first();

                    if (!$existingPort) {
                        // Simpan port baru
                        $port = $host->ports()->create([
                            'port_number' => $portNumber,
                            'asset_group' => 'Unknown', // Update sesuai data yang Anda butuhkan
                            'trigger' => 'New port detected', // Update sesuai data yang Anda butuhkan
                            'version' => 'Unknown', // Update sesuai data yang Anda butuhkan
                            'details' => json_encode(['port' => $portNumber]),
                        ]);

                        // Kirim email ke pengguna ketika port baru ditemukan
                        $this->sendPortNewEmail($host->email, $port);
                    }
                }

                return response()->json(['message' => 'Port data processed and email sent.']);
            } else {
                return response()->json(['message' => 'No open ports found or error occurred.'], 400);
            }

        } catch (RequestException $e) {
            return response()->json(['message' => 'Error calling Shodan API: ' . $e->getMessage()], 500);
        }
    }
}
