<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DehashedService
{
    public function search($domain)
    {
        $url = 'https://api.dehashed.com/search';
        $query = 'domain:' . $domain;

        // Mengambil username dan password dari .env
        $username = env('DEHASHED_USERNAME');
        $password = env('DEHASHED_PASSWORD');

        // Kirim request ke API
        $response = Http::withBasicAuth($username, $password)
            ->accept('application/json')
            ->get($url, ['query' => $query]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
