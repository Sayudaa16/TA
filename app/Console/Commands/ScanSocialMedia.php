<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BrandMention;
use Illuminate\Support\Facades\Http;

class ScanSocialMedia extends Command
{
    protected $signature = 'scan:socialmedia';
    protected $description = 'Scan recent brand mentions from Google Custom Search API';

    private $apiKey = 'AIzaSyA_upa6nH9Y6XlDAEr2YJUUxJPfT8lzfNE';
    private $cx = '333a6c9547b904ece';

    private $queries = [
        // Facebook
        'site:facebook.com "Bank Tabungan Negara" OR "BTN"',
        'site:facebook.com "Bank Tabungan Negara" AND "pegawai"',

        // Instagram
        'site:instagram.com "Bank Tabungan Negara" OR "BTN"',

        // YouTube
        'site:youtube.com "Bank Tabungan Negara" OR "BTN"',

        // Twitter (X)
        'site:twitter.com "Bank Tabungan Negara" OR "BTN"',

        // LinkedIn
        'site:linkedin.com/in "Bank Tabungan Negara"',
        'site:linkedin.com/company "Bank Tabungan Negara"',

        // TikTok
        'site:tiktok.com "Bank Tabungan Negara" OR "BTN"',

        // Telegram (meskipun tidak semua public group bisa diindeks)
        'site:t.me "Bank Tabungan Negara" OR "BTN"',

        // Reddit
        'site:reddit.com "Bank Tabungan Negara" OR "BTN"',

        // GitHub
        'site:github.com "btn.co.id" OR "Bank Tabungan Negara"',

        // Vimeo
        'site:vimeo.com "Bank Tabungan Negara" OR "BTN"',

        // RSS / Blog
        'inurl:rss "Bank Tabungan Negara" OR "BTN"',
        'inurl:feed "Bank Tabungan Negara" OR "BTN"',

        // Comments / Forum
        'inurl:comment "Bank Tabungan Negara" OR "BTN"',
        'inurl:forum "Bank Tabungan Negara" OR "BTN"',
    ];


    public function handle()
    {
        $this->info('🚀 Mulai pencarian...');

        foreach ($this->queries as $query) {
            $this->info("🔍 Mencari: $query");

            foreach ($this->searchGoogle($query) as $url) {
                $platform = $this->detectPlatform($url);

                // Cek duplikasi sebelum simpan
                if (!BrandMention::where('url', $url)->exists()) {
                    BrandMention::create([
                        'platform' => $platform,
                        'url' => $url,
                    ]);
                    $this->line("✅ [$platform] $url");
                } else {
                    $this->line("⚠️ Duplikat dilewati: $url");
                }
            }
        }

        $this->info('🎉 Selesai!');
    }

    private function searchGoogle($query, $numPages = 3)
    {
        $results = [];

        for ($page = 0; $page < $numPages; $page++) {
            $start = $page * 10 + 1;

            $response = Http::get("https://www.googleapis.com/customsearch/v1", [
                'q'     => $query,
                'key'   => $this->apiKey,
                'cx'    => $this->cx,
                'start' => $start,
                'sort'  => 'date',
            ]);

            if ($response->successful() && isset($response['items'])) {
                foreach ($response['items'] as $item) {
                    $results[] = $item['link'];
                }
            } else {
                $this->error("❌ Gagal request halaman ke-$page untuk query: $query");
                break;
            }
        }

        return array_unique($results);
    }

    private function detectPlatform($url)
    {
        $map = [
            'facebook.com'  => 'facebook',
            'instagram.com' => 'instagram',
            'twitter.com'   => 'twitter',
            'linkedin.com'  => 'linkedin',
            'youtube.com'   => 'youtube',
            'tiktok.com'    => 'tiktok',
            'github.com'    => 'github',
            'scribd.com'    => 'scribd',
            'slideshare.net'=> 'slideshare',
            'reddit.com'    => 'reddit',
            'btn.co.id'     => 'btn',
        ];

        foreach ($map as $domain => $platform) {
            if (str_contains($url, $domain)) {
                return $platform;
            }
        }

        return 'unknown';
    }
}
