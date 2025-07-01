<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\CyberSecurityNews;

class FetchCyberSecurityNews extends Command
{
    protected $signature = 'fetch:cyber-news';
    protected $description = 'Fetch Cyber Security News and save to database';

    public function handle()
    {
        $feedUrl = 'https://feeds.feedburner.com/cyber-security-news';
        $response = Http::get($feedUrl);

        if ($response->ok()) {
            $xmlContent = str_replace('&', '&amp;', $response->body());
            $xml = simplexml_load_string($xmlContent, 'SimpleXMLElement', LIBXML_NOERROR | LIBXML_NOWARNING);

            foreach ($xml->channel->item as $item) {
                $newsData = [
                    'title' => (string) $item->title,
                    'description' => (string) $item->description,
                    'url' => (string) $item->link,
                    'url_to_image' => (string) ($item->enclosure['url'] ?? $item->children('media', true)->content->attributes()->url ?? null),
                    'published_at' => date('Y-m-d H:i:s', strtotime((string) $item->pubDate)),
                ];

                CyberSecurityNews::updateOrCreate(['url' => $newsData['url']], $newsData);
            }

            // Panggil script klasifikasi setelah data diambil
            exec('python storage/scripts/classify_news.py');

            $this->info('Cyber Security News updated successfully.');
        } else {
            $this->error('Failed to fetch Cyber Security News.');
        }
    }
}

