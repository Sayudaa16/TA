<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException; // Tambahkan ini untuk penanganan kesalahan

class ShodanController extends Controller
{
    public function index()
    {
        // Menampilkan halaman pencarian Shodan
        return view('shodan_search');
    }

    public function search(Request $request)
    {
        // Mengambil API key Shodan dari file .env
        $apiKey = env('SHODAN_API_KEY');  // Menggunakan env() untuk mendapatkan API key dari .env
        $query = $request->input('query'); // Parameter pencarian dari pengguna

        $client = new Client();
        
        try {
            $response = $client->request('GET', 'https://api.shodan.io/shodan/host/search', [
                'query' => [
                    'key' => $apiKey,
                    'query' => $query,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal melakukan pencarian ke Shodan: ' . $e->getMessage()], 500);
        }
    }
    
}
