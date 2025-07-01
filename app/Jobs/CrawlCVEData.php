<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\CVE;

class CrawlCVEData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $username = 'revanzavarmillion';
        $password = 'rohis1403';
        $page = 1;

        do {
            $url = "https://app.opencve.io/api/cve?page={$page}";
            $response = Http::withBasicAuth($username, $password)->get($url);

            if (!$response->successful()) {
                break;
            }

            $data = $response->json();
            $results = $data['results'] ?? [];

            foreach ($results as $cve) {
                if (!isset($cve['cve_id'])) {
                    continue;
                }

                $existingCVE = CVE::where('cve_id', $cve['cve_id'])->first();
                $apiUpdatedAt = isset($cve['updated_at']) ? Carbon::parse($cve['updated_at']) : now();

                if ($existingCVE) {
                    if ($apiUpdatedAt->gt($existingCVE->updated_at_api)) {
                        $existingCVE->update([
                            'description' => $cve['description'] ?? '',
                            'updated_at_api' => $apiUpdatedAt,
                            'updated_at' => now(),
                        ]);
                    }
                } else {
                    CVE::create([
                        'cve_id' => $cve['cve_id'],
                        'description' => $cve['description'] ?? '',
                        'created_at_api' => $cve['created_at'] ?? now(),
                        'updated_at_api' => $apiUpdatedAt,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]);
                }
            }

            $page++;
        } while (!empty($results));
    }
}
