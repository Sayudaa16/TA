<?php

namespace App\Services;

use GuzzleHttp\Client;

class NewsAPIService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://newsapi.org/v2/']);
    }

    public function getLatestNews()
    {
        try {
            $response = $this->client->request('GET', 'everything', [
                'query' => [
                    'q' => 'cybersecurity', // Berita terkait cybersecurity
                    'sortBy' => 'publishedAt',
                    'apiKey' => env('NEWSAPI_KEY'), // API Key dari NewsAPI
                ],
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            \Log::error('NewsAPI Error: ' . $e->getMessage());
            return ['articles' => []]; // Return kosong jika error
        }
    }
}
