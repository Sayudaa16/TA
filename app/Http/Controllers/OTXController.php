<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;

class OTXController extends Controller
{
    private $apiKey = "ff22daa1e66463916add9e0dcc9b1f911f8474d04143fcdd5c44ce1165c0efe6";

    // Menampilkan daftar pulses yang sudah di-subscribe atau hasil pencarian
    public function index(Request $request)
    {
        $searchResults = null;
        if ($request->has('q')) {
            $searchResults = $this->search($request->input('q'));
            return view('otx.index', compact('searchResults'))->with('pulses', ['results' => []]);
        }

        $response = Http::withHeaders([
            'X-OTX-API-KEY' => $this->apiKey
        ])->get('https://otx.alienvault.com/api/v1/pulses/subscribed', [
            'limit' => 10, // Ambil hingga 50 pulses dalam satu permintaan
            'page' => request()->get('page', 1) // Ambil halaman pertama jika tidak ada parameter
        ]);

        $pulses = $response->successful() ? $response->json() : ['results' => []];

        return view('otx.index', compact('pulses', 'searchResults'));
    }

    // Fungsi pencarian menggunakan API
    public function getData(Request $request)
    {
        $page = $request->page ?? 1;
        $query = $request->input('search');
        $perPage = 5;

        if ($query) {
            $url = 'https://otx.alienvault.com/api/v1/search/pulses';
            $params = ['q' => $query, 'limit' => $perPage, 'page' => $page];
        } else {
            $url = 'https://otx.alienvault.com/api/v1/pulses/subscribed';
            $params = ['limit' => $perPage, 'page' => $page];
        }

        $response = Http::withHeaders([
            'X-OTX-API-KEY' => $this->apiKey
        ])->get($url, $params);

        $json = $response->json();

        $items = collect($json['results']);
        $total = $json['count'] ?? ($page * $perPage + ($items->count() < $perPage ? 0 : $perPage));

        $paginator = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return response()->json($paginator);
    }

    // Menampilkan detail pulse berdasarkan ID
    public function show($id)
    {
        $response = Http::withHeaders([
            'X-OTX-API-KEY' => $this->apiKey
        ])->get("https://otx.alienvault.com/api/v1/pulses/{$id}");

        if ($response->successful()) {
            $pulse = $response->json();
            return view('otx.detail', compact('pulse'));
        }

        return back()->with('error', 'Pulse tidak ditemukan.');
    }

    // Download Pulse dalam bentuk PDF
    public function downloadPdf($id)
    {
        $response = Http::withHeaders([
            'X-OTX-API-KEY' => $this->apiKey
        ])->get("https://otx.alienvault.com/api/v1/pulses/{$id}");

        if (!$response->successful()) {
            return back()->with('error', 'Pulse tidak ditemukan.');
        }

        $pulse = $response->json();
        $pdf = Pdf::loadView('otx.pdf', compact('pulse'));
        $filename = 'Pulse_' . ($pulse['id'] ?? 'Unknown') . '.pdf';

        return $pdf->download($filename);
    }
}
