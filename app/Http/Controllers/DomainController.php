<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Domain;
use App\Models\Subdomain;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\WhoisAPIService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class DomainController extends Controller
{
    protected $whoisService;

    public function __construct(WhoisAPIService $whoisService)
    {
        $this->whoisService = $whoisService;
    }

    public function destroy($id)
    {
        $domain = Domain::findOrFail($id); // Ganti `Domain` dengan nama model Anda
        $domain->delete();

        return redirect()->back()->with('success', 'Domain deleted successfully.');
    }

    public function index()
    {
        $domains = Domain::all();
        $records = Subdomain::all();

        return view('domain.domain', compact('domains', 'records'));
    }

    public function fetchAndStoreDomainData(Request $request)
    {
        $request->validate([
            'domain' => 'required|url',
        ]);

        $domainName = parse_url($request->domain, PHP_URL_HOST);
        $domainData = $this->whoisService->fetchDomainData($domainName);

        if ($domainData) {
            $registryData = $domainData['WhoisRecord']['registryData'];

            $domain = Domain::updateOrCreate(
                ['domain_name' => $domainName],
                [
                    'expiry_date' => Carbon::parse($registryData['expiresDate'])->format('Y-m-d'),
                    'ssl_expiry_date' => null, // Tambahkan logika untuk SSL jika ada
                    'registrar_name' => $registryData['registrarName'] ?? null,
                    'created_date' => Carbon::parse($registryData['createdDate'])->format('Y-m-d H:i:s'),
                    'updated_date' => Carbon::parse($registryData['updatedDate'])->format('Y-m-d H:i:s'),
                    'name_servers' => json_encode($registryData['nameServers']['hostNames'] ?? []),
                    'domain_status' => $registryData['status'] ?? null,
                    'additional_info' => json_encode($domainData), // Simpan data mentah untuk referensi
                    'organization_id' => Auth::user()->organization_id, // Ambil dari user yang login
                ]
            );

            return redirect()->route('domains.index')->with('success', 'Domain berhasil ditambahkan.');
        }

        return redirect()->back()->with('error', 'Gagal mengambil data domain.');
    }

    // public function subdomain()
    // {
    //         // URL API dengan domain yang diinginkan
    //         $apiUrl = 'https://subdomains.whoisxmlapi.com/api/v1';
    //         $apiKey = 'at_5SxsDTwFo6BS58R90Bi3pal5lg06t';
    //         $domainName = 'obf.id';

    //         // Panggil API menggunakan Facade HTTP
    //         $response = Http::get($apiUrl, [
    //             'apiKey' => $apiKey,
    //             'domainName' => $domainName,
    //         ]);

    //         // Periksa apakah respons berhasil
    //         if ($response->successful()) {
    //             // Parse data JSON dari API
    //             $data = $response->json();

    //             // Kirim data ke view untuk ditampilkan
    //             return view('domain.subdomain', [
    //                 'search' => $data['search'] ?? null,
    //                 'records' => $data['result']['records'] ?? [],
    //             ]);
    //         }

    //         // Jika gagal, kembalikan error
    //         return back()->withErrors('Gagal mendapatkan data subdomain dari API.');
    // }

    // Method untuk download PDF
    public function downloadPdf()
    {
        $domains = Domain::all();

        // Generate PDF dengan data domain
        $pdf = PDF::loadView('pdfdomain', compact('domains'));

        // Download file PDF
        return $pdf->download('domain_report.pdf');
    }

    public function organization(Request $request)
    {
        $organizationId = $request->input('organization_id'); // Dapatkan organisasi aktif
        $domains = Domain::where('organization_id', $organizationId)->get();

        return view('domain.domain', ['domains' => $domains]);
    }
}
