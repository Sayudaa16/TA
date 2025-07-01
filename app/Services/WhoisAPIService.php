<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhoisAPIService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('WHOIS_API_KEY'); // Simpan API Key di file .env
    }

    public function fetchDomainData($domain)
    {
        $response = Http::get('https://www.whoisxmlapi.com/whoisserver/WhoisService', [
            'apiKey' => $this->apiKey,
            'domainName' => $domain,
            'outputFormat' => 'JSON',
        ]);

        if ($response->ok()) {
            return $response->json();
        }

        return null;
    }
}
