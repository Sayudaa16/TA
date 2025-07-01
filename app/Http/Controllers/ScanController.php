<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Client\RequestException;

class ScanController extends Controller
{
    protected $client;
    protected $shodanApiKey;
    protected $virusTotalApiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->shodanApiKey = env('SHODAN_API_KEY'); // Simpan API Key di .env
        $this->virusTotalApiKey = env('VIRUS_TOTAL_API_KEY'); // Simpan API Key VirusTotal di .env
        $this->otxApiKey = env('OTX_API_KEY');
    }

    public function index()
    {
        return view('scanner'); // Buat view shodan.blade.php untuk antarmuka pengguna
    }

    public function scan(Request $request)
    {
        $ip = $request->input('ip');
        $data = [];

        try {
            // Mengambil informasi host dari Shodan
            $response = $this->client->get("https://api.shodan.io/shodan/host/$ip?key={$this->shodanApiKey}");
            $result = json_decode($response->getBody(), true);

            $data = [
                'ip' => $result['ip_str'] ?? 'N/A',
                'os' => $result['os'] ?? 'N/A',
                'isp' => $result['isp'] ?? 'N/A',
                'org' => $result['org'] ?? 'N/A',
                'ports' => $result['ports'] ?? [],
                'vulns' => $result['vulns'] ?? [],
                'latitude' => $result['latitude'] ?? null,
                'longitude' => $result['longitude'] ?? null,
            ];
        } catch (RequestException $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal mengambil data dari Shodan: ' . $e->getMessage()]);
        }

        // Ambil detail CVE
        $data['cve_details'] = [];
        foreach ($data['vulns'] as $cve) {
            try {
                // Ambil data dari Circle.LU
                $cveResponse = $this->client->get("https://cve.circl.lu/api/cve/$cve");
                $cveData = json_decode($cveResponse->getBody(), true);

                // Pastikan deskripsi tersedia
                if (!empty($cveData['containers']['cna']['descriptions'])) {
                    $descriptions = $cveData['containers']['cna']['descriptions'];
                    $description = collect($descriptions)->firstWhere('lang', 'en')['value'] ?? 'Deskripsi tidak tersedia';
                } else {
                    $description = 'Deskripsi tidak tersedia';
                }

                $data['cve_details'][$cve] = $description;
            } catch (RequestException $e) {
                $data['cve_details'][$cve] = 'Gagal mengambil data CVE: ' . $e->getMessage();
            }
        }

        // Integrasi VirusTotal
        try {
            $virusTotalResponse = $this->client->get("https://www.virustotal.com/api/v3/ip_addresses/$ip", [
                'headers' => [
                    'x-apikey' => $this->virusTotalApiKey,
                ],
            ]);

            $virusTotalResult = json_decode($virusTotalResponse->getBody(), true);
            $attributes = $virusTotalResult['data']['attributes'] ?? [];

            $data['virus_total'] = [
                'community_score' => $attributes['last_analysis_stats']['malicious'] ?? 0,
                'last_analysis_date' => $attributes['last_analysis_date'] ?? 'N/A',
                'last_analysis_results' => $attributes['last_analysis_results'] ?? [],
            ];
        } catch (RequestException $e) {
            $data['virus_total'] = 'Gagal mengambil data dari VirusTotal: ' . $e->getMessage();
        }

      // Integrasi OTX
        try {
            $otxResponse = $this->client->get("https://otx.alienvault.com/api/v1/indicators/IPv4/{$ip}", [
                'headers' => [
                    'X-OTX-API-KEY' => $this->otxApiKey,
                ],
            ]);

            $otxResult = json_decode($otxResponse->getBody(), true);

            // Menyimpan lebih banyak informasi
            $data['otx'] = [
                'threat_level' => $otxResult['pulse_info']['threat_level'] ?? 'Tidak tersedia',
                'description' => $otxResult['pulse_info']['description'] ?? 'Tidak tersedia',
                'pulse_count' => $otxResult['pulse_info']['pulse_count'] ?? 'Tidak tersedia',
                'first_seen' => $otxResult['pulse_info']['first_seen'] ?? 'Tidak tersedia',
                'last_seen' => $otxResult['pulse_info']['last_seen'] ?? 'Tidak tersedia',
            ];
        } catch (RequestException $e) {
            $data['otx'] = 'Gagal mengambil data dari OTX: ' . $e->getMessage();
        }

        // Data statistik untuk Chart.js
        $data['chart_data'] = [
            'ports_open' => count($data['ports']),
            'cve_count' => count($data['vulns']),
            'malicious_count' => $data['virus_total']['community_score'] ?? 0,
        ];

        return view('scanner', compact('data'));
    }
}
