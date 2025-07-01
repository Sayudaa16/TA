<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VipImpersonate;
use Illuminate\Support\Facades\Http;

class FetchVipLinks extends Command
{
    protected $signature = 'vip:fetch-links';
    protected $description = 'Fetch links related to VIP impersonation using Google Custom Search';

    public function handle()
    {
        $apiKey = "AIzaSyAaIosP3zWXqS1Urir-kWN_A4atKuCZ8Cg";
        $cx = "c797ddd00903b49b7";

        $targetName = "Zulkifli";

        $queries = [
            'facebook' => 'site:facebook.com "' . $targetName . '"',
            'instagram' => 'site:instagram.com "' . $targetName . '"',
            'youtube' => 'site:youtube.com "' . $targetName . '"',
            'twitter' => 'site:twitter.com "' . $targetName . '"',
            'linkedin' => 'site:linkedin.com "' . $targetName . '"',
            'tiktok' => 'site:tiktok.com "' . $targetName . '"',
            'telegram' => 'site:t.me "' . $targetName . '"',
            'reddit' => 'site:reddit.com "' . $targetName . '"',
            'github' => 'site:github.com "' . $targetName . '"',
            'vimeo' => 'site:vimeo.com "' . $targetName . '"',
            'rss' => 'inurl:rss "' . $targetName . '"',
            'feed' => 'inurl:feed "' . $targetName . '"',
            'comment' => 'inurl:comment "' . $targetName . '"',
            'forum' => 'inurl:forum "' . $targetName . '"',
        ];

        $accountPatterns = [
            'facebook' => '#^https://(www\.)?facebook\.com/[^/]+/?$#',
            'instagram' => '#^https://(www\.)?instagram\.com/[^/]+/?$#',
            'youtube' => '#^https://(www\.)?youtube\.com/(c|channel|user)/[^/]+/?$#',
            'twitter' => '#^https://(www\.)?twitter\.com/[^/]+/?$#',
            'linkedin' => '#^https://(www\.)?linkedin\.com/in/[^/]+/?$#',
            'tiktok' => '#^https://(www\.)?tiktok\.com/@[^/]+/?$#',
            'telegram' => '#^https://t\.me/[^/]+/?$#',
            'reddit' => '#^https://(www\.)?reddit\.com/user/[^/]+/?$#',
            'github' => '#^https://(www\.)?github\.com/[^/]+/?$#',
            'vimeo' => '#^https://(www\.)?vimeo\.com/[^/]+/?$#',
        ];

        foreach ($queries as $platform => $query) {
            $this->info("🔍 Searching on: {$platform}");

            for ($page = 0; $page < 3; $page++) {
                $start = $page * 10 + 1;
                $url = "https://www.googleapis.com/customsearch/v1?q=" . urlencode($query) . "&key=$apiKey&cx=$cx&start=$start&sort=date";

                $response = Http::get($url);

                if ($response->ok()) {
                    $items = $response->json('items') ?? [];

                    foreach ($items as $item) {
                        $link = $item['link'];

                        // Validasi format URL akun (jika ada pola untuk platform)
                        if (isset($accountPatterns[$platform]) && !preg_match($accountPatterns[$platform], $link)) {
                            continue;
                        }

                        // Ambil isi halaman untuk validasi nama
                        try {
                            $pageContent = Http::timeout(5)->get($link)->body();

                            // Cek apakah konten halaman mengandung nama target
                            if (stripos($pageContent, $targetName) === false) {
                                continue;
                            }

                            VipImpersonate::firstOrCreate([
                                'platform' => $platform,
                                'url' => $link,
                            ]);
                        } catch (\Exception $e) {
                            $this->warn("⚠️ Gagal ambil halaman: $link");
                            continue;
                        }
                    }
                } else {
                    $this->error("❌ Error: {$response->status()}");
                    break;
                }
            }
        }

        $this->info("✅ Data updated!");
    }
}
