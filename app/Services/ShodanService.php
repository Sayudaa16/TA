<?php
//app/Services/ShodanService.php

namespace App\Services;

use GuzzleHttp\Client;

class ShodanService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('SHODAN_API_KEY');
    }

    public function scanHost($ip)
    {
        try {
            $response = $this->client->get("https://api.shodan.io/shodan/host/$ip?key={$this->apiKey}");
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return null;
        }
    }
}
