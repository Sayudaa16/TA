<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Vulnerability;
use Carbon\Carbon;
use Log;

class CrawlCVEData extends Command
{
    protected $signature = 'crawl:cvedata';
    protected $description = 'Crawl latest CVE data and store it in the database';

    public function handle()
    {
        $url = "https://cve.circl.lu/api/vulnerability/last";
        $headers = [
            'accept' => 'application/json',
            'X-API-KEY' => 'ru1oeiel55o4k473XG9JAxieE2FA9e2VO0KUIrw5m8XfWHgW9JleCMQ-cI9JCcK2tR6sGVMN2PpEFBDG8Wg',
        ];

        try {
            $response = Http::withHeaders($headers)->timeout(30)->get($url);
            if ($response->successful()) {
                $data = $response->json();

                $filteredData = array_filter($data, function ($item) {
                    $publishedDate = $item['cveMetadata']['datePublished'] ?? null;
                    return $publishedDate && strtotime($publishedDate) >= strtotime('2024-01-01');
                });

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

                $this->info('CVE data successfully crawled and stored.');
            }
        } catch (\Exception $e) {
            Log::error('API Fetch Error: ' . $e->getMessage());
        }
    }
}
