<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\CVE;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\Vulnerability;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CVEController extends Controller
{
    // Tidak menggunakan Http sebagai trait

    public function index()
    {
        // Ambil data CVE terbaru dari database
        $vulnerabilities = Vulnerability::orderBy('published_at', 'desc')->get();

        // Hitung statistik untuk dashboard
        $newCveToday = Vulnerability::whereDate('published_at', Carbon::today())->count();
        $updatedToday = Vulnerability::whereDate('updated_at', Carbon::today())->count();
        $weeklyExploited = Vulnerability::whereBetween('published_at', [Carbon::now()->subDays(7), Carbon::now()])->count();

        // Data untuk chart
        $vulnerabilityData = Vulnerability::selectRaw('DATE(published_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count', 'date');

        $severityDistribution = [
            'Low' => Vulnerability::where('cvss_score', '<', 4)->count(),
            'Medium' => Vulnerability::whereBetween('cvss_score', [4, 6.9])->count(),
            'High' => Vulnerability::where('cvss_score', '>=', 7)->count(),
        ];

        return view('dashboard.vulndashboard', compact(
            'newCveToday',
            'updatedToday',
            'weeklyExploited',
            'vulnerabilityData',
            'severityDistribution',
            'vulnerabilities'
        ));
    }

        public function cve()
        {
            // Memeriksa apakah file cve.json ada di storage
            $filePath = storage_path('app/cve.json');
            if (file_exists($filePath)) {
                // Membaca konten file JSON
                $vulnerabilities = json_decode(file_get_contents($filePath), true);

                // Memeriksa apakah data ditemukan
                if (isset($vulnerabilities) && is_array($vulnerabilities)) {
                    return view('cvefeed.index', compact('vulnerabilities'));
                } else {
                    return view('cvefeed.index')->with('error', 'No vulnerabilities data found.');
                }
            } else {
                return view('cvefeed.index')->with('error', 'CVE file not found.');
            }
        }

    public function opencve(Request $request)
{
    $query = $request->input('query');
    $filterType = $request->input('filter_type');

    // Jika ada query pencarian, ambil dari API
    if ($query) {
        $username = 'revanzavarmillion';
        $password = 'rohis1403';

        $url = match ($filterType) {
            'vendors' => "https://app.opencve.io/api/vendors/{$query}/cve",
            'products' => "https://app.opencve.io/api/vendors/{$query}/products",
            default => "https://app.opencve.io/api/cve/{$query}",
        };

        $response = Http::withBasicAuth($username, $password)->get($url);

        if ($response->successful()) {
            $data = $response->json();
            $results = match ($filterType) {
                'vendors', 'products' => $data['results'] ?? [],
                default => [$data],
            };

            return view('opencve.index', [
                'results' => $results,
                'total' => count($results),
                'currentPage' => 1,
                'isSearch' => true, // Indikasi bahwa ini adalah hasil pencarian API
            ]);
        }

        return view('opencve.index', [
            'results' => [],
            'total' => 0,
            'currentPage' => 1,
            'isSearch' => true,
            'error' => 'Data tidak ditemukan atau terjadi kesalahan saat mengambil data dari API.',
        ]);
    }

    // Jika tidak ada pencarian, ambil dari database dengan pagination
    $cves = CVE::orderBy('updated_at_api', 'desc')->paginate(10);

    return view('opencve.index', [
        'results' => $cves,
        'total' => $cves->total(),
        'currentPage' => $cves->currentPage(),
        'isSearch' => false, // Indikasi bahwa ini adalah data dari database
    ]);
}
    public function showCVE($id)
    {
        $username = 'revanzavarmillion';
        $password = 'rohis1403';

        $response = Http::withBasicAuth($username, $password)
            ->get("https://app.opencve.io/api/cve/{$id}");

        if ($response->successful()) {
            $data = $response->json();

            // Tambahkan default value untuk data kosong
            $data['metrics']['cvssV3_1'] = $data['metrics']['cvssV3_1']['data']['score'] ?? 'N/A';
            $data['metrics']['kev'] = $data['metrics']['kev']['provider'] ?? 'N/A';
            $data['metrics']['ssvc'] = $data['metrics']['ssvc']['provider'] ?? 'N/A';
            $data['vendors'] = $data['vendors'] ?? [];
            $data['references'] = $data['references'] ?? [];
            $data['history'] = $data['history'] ?? [];

            return view('opencve.details', ['cve' => $data]);
        }

        abort(404, 'CVE not found.');
    }

//     public function searchCVE(Request $request)
// {
//     $query = $request->input('query');
//     $filterType = $request->input('filter_type'); // 'cve' atau 'vendors'

//     $username = 'revanzavarmillion';
//     $password = 'rohis1403';

//     $url = $filterType === 'product'
//         ? "https://app.opencve.io/api/vendors/{$query}/cve"
//         : "https://app.opencve.io/api/cve/{$query}";

//     try {
//         $response = Http::withBasicAuth($username, $password)->get($url);

//         if ($response->successful()) {
//             $data = $response->json();

//             // Data untuk tabel
//             $results = $filterType === 'product' ? $data['results'] : [$data];

//             return view('opencve.index', [
//                 'results' => $results,
//                 'total' => count($results),
//             ]);
//         } else {
//             return redirect()->back()->with('error', 'No data found.');
//         }
//     } catch (\Exception $e) {
//         return redirect()->back()->with('error', 'Failed to fetch data: ' . $e->getMessage());
//     }
// }


    // public function nvd()
    // {
    //     try {
    //         // Ambil API key dari .env
    //         $apiKey = env('NVD_API_KEY');

    //         // Endpoint API
    //         $url = "https://services.nvd.nist.gov/rest/json/cves/2.0";

    //         // Mengambil data dari API
    //         $response = Http::withHeaders([
    //             'Content-Type' => 'application/json',
    //             'apiKey' => $apiKey,
    //         ])->get($url);

    //         // Cek status respons
    //         if ($response->successful()) {
    //             $data = $response->json();

    //             // Ekstrak kerentanannya
    //             $vulnerabilities = collect($data['vulnerabilities'])->map(function ($vulnerability) {
    //                 $cve = $vulnerability['cve'];
    //                 $cvss = $cve['metrics']['cvssMetricV2'][0] ?? null;
    //                 return [
    //                     'Title' => $cve['id'],
    //                     'Description' => $cve['descriptions'][0]['value'] ?? 'No description available',
    //                     'Published' => $cve['published'],
    //                     'LastModified' => $cve['lastModified'],
    //                     'CVSS_Score' => $cvss['cvssData']['baseScore'] ?? 'Not Available',
    //                     'Detail_URL' => "https://nvd.nist.gov/vuln/detail/" . $cve['id'],
    //                 ];
    //             });

    //             // Filter kerentanannya yang dipublikasikan setelah tahun 2000
    //             $vulnerabilities = $vulnerabilities->filter(function ($vuln) {
    //                 $publishedYear = substr($vuln['Published'], 0, 4);
    //                 return $publishedYear >= 2000;
    //             });

    //             // Bulan saat ini dalam format 'YYYY-MM'
    //             $currentMonth = now()->format('Y-m');

    //             // Pisahkan kerentanannya yang terbaru (dipublikasikan atau terakhir dimodifikasi bulan ini)
    //             $latest = $vulnerabilities->filter(function ($vuln) use ($currentMonth) {
    //                 return strpos($vuln['Published'], $currentMonth) === 0 ||
    //                        strpos($vuln['LastModified'], $currentMonth) === 0;
    //             });

    //             // Sisa kerentanannya
    //             $others = $vulnerabilities->reject(function ($vuln) use ($currentMonth) {
    //                 return strpos($vuln['Published'], $currentMonth) === 0 ||
    //                        strpos($vuln['LastModified'], $currentMonth) === 0;
    //             });

    //             // Gabungkan yang terbaru dan lainnya
    //             $vulnerabilities = $latest->merge($others);

    //             return view('nvd.index', compact('vulnerabilities'));
    //         } else {
    //             return redirect()->back()->with('error', 'Failed to fetch data from NVD API.');
    //         }
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
    //     }
    // }

    public function circl()
    {
        // URL endpoint API
        $url = "https://cve.circl.lu/api/vulnerability/last";

        // Header API key
        $headers = [
            'accept' => 'application/json',
            'X-API-KEY' => 'ru1oeiel55o4k473XG9JAxieE2FA9e2VO0KUIrw5m8XfWHgW9-w9JleCMQ-cI9JCcK2tR6sGVMN2PpEFBDG8Wg',
        ];

        try {
            // Melakukan permintaan GET ke API dengan batas waktu 30 detik
            $response = Http::withHeaders($headers)->timeout(30)->get($url);

            // Memastikan status response sukses
            if ($response->successful()) {
                $data = $response->json();

                // Filter data berdasarkan rentang waktu (Januari 2024 ke atas)
                $filteredData = array_filter($data, function ($item) {
                    $publishedDate = $item['cveMetadata']['datePublished'] ?? null;
                    return $publishedDate && strtotime($publishedDate) >= strtotime('2024-01-01');
                });

                // Simpan data ke database jika belum ada
                foreach ($filteredData as $item) {
                    $cveId = $item['cveMetadata']['cveId'] ?? null;
                    $publishedAt = $item['cveMetadata']['datePublished'] ?? null;

                    if ($cveId && !Vulnerability::where('cve_id', $cveId)->exists()) {
                        Vulnerability::create([
                            'cve_id' => $cveId,
                            'description' => $item['containers']['cna']['descriptions'][0]['value'] ?? 'No description available',
                            'cvss_score' => $item['containers']['cna']['metrics'][0]['cvssV3_1']['baseScore'] ?? null,
                            'published_at' => $publishedAt ? Carbon::parse($publishedAt)->format('Y-m-d H:i:s') : null,
                            'detail_url' => "https://cve.mitre.org/cgi-bin/cvename.cgi?name=" . $cveId,
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            // Jika terjadi error (termasuk timeout), tampilkan log error
            Log::error('API Fetch Error: ' . $e->getMessage());
        }

        // Ambil data dari database jika API gagal atau berhasil
        $vulnerabilities = Vulnerability::orderBy('published_at', 'desc')->get();

        // Pisahkan data latest dan other
        $latestVulnerabilities = $vulnerabilities->take(8); // 8 data terbaru
        $otherVulnerabilities = $vulnerabilities->skip(8); // Data lainnya

        // Kirim data ke view
        return view('circl.index', compact('latestVulnerabilities', 'otherVulnerabilities'));
    }
    // public function showDetail($cveId)
    // {
    //     // Endpoint API untuk mendapatkan detail CVE berdasarkan cveId
    //     $url = "https://cve.circl.lu/api/vulnerability/" . $cveId;

    //     // Header API key
    //     $headers = [
    //         'accept' => 'application/json',
    //         'X-API-KEY' => 'YOUR_API_KEY', // Ganti dengan API Key yang benar
    //     ];

    //     // Mengambil data dari API
    //     $response = Http::withHeaders($headers)->get($url);

    //     // Memeriksa apakah respons berhasil
    //     if ($response->successful()) {
    //         // Mengambil data dari response
    //         $data = $response->json();
    //         return view('circl.detail', compact('data'));
    //     } else {
    //         return redirect()->route('circl.index')->with('error', 'Failed to fetch CVE details');
    //     }
    // }

// public function others()
// {
//     $url = "https://cve.circl.lu/api/vulnerability/last";
//     $headers = [
//         'accept' => 'application/json',
//         'X-API-KEY' => 'ru1oeiel55o4k473XG9JAxieE2FA9e2VO0KUIrw5m8XfWHgW9-w9JleCMQ-cI9JCcK2tR6sGVMN2PpEFBDG8Wg',
//     ];

//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//     $response = curl_exec($ch);
//     curl_close($ch);

//     // Mengubah response JSON menjadi array PHP
//     $data = json_decode($response, true);

//     // Periksa apakah ada data
//     if ($data) {
//         $response_data = []; // Array untuk menampung hasil vulnerabilities

//         foreach ($data as $entry) {
//             // Pastikan data memiliki struktur yang sesuai
//             if (isset($entry['cveMetadata'], $entry['containers']['cna'])) {
//                 $cveMetadata = $entry['cveMetadata'];
//                 $cna = $entry['containers']['cna'];

//                 $response_data[] = [
//                     'cveId' => $cveMetadata['cveId'] ?? null,
//                     'title' => $cna['title'] ?? 'No Title',
//                     'description' => $cna['descriptions'][0]['value'] ?? 'No Description',
//                     'cvssScore' => $cna['metrics'][0]['cvssV3_1']['baseScore'] ?? null,
//                     'severity' => $cna['metrics'][0]['cvssV3_1']['baseSeverity'] ?? null,
//                     'affected' => $cna['affected'][0]['product'] ?? 'Unknown Product',
//                     'references' => array_map(fn($ref) => $ref['url'], $cna['references'] ?? []),
//                 ];
//             }
//         }

//         // Mengirimkan data ke view
//         return view('circl.others', compact('response_data'));
//     } else {
//         return response()->json(['error' => 'No vulnerabilities found'], 400);
//     }
// }
}

