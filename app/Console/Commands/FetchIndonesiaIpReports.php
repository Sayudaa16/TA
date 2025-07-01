<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\IndonesiaIpReport;

class FetchIndonesiaIpReports extends Command
{
    protected $signature = 'fetch:indonesia-ip-reports';
    protected $description = 'Fetch recent IP reports from Project Honey Pot for Indonesia';

    public function handle()
    {
        $feedUrl = 'https://www.projecthoneypot.org/list_of_ips.php?t=h&ctry=id&rss=1';
        $response = Http::get($feedUrl);

        if ($response->ok()) {
            $xml = simplexml_load_string($response->body());

            foreach ($xml->channel->item as $item) {
                $ip = trim(explode('|', (string) $item->title)[0]);
                $riskLevel = trim(explode('|', (string) $item->title)[1] ?? '');
                $description = (string) $item->description;
                $url = (string) $item->link;
                $reportedAt = date('Y-m-d H:i:s', strtotime((string) $item->pubDate));

                IndonesiaIpReport::updateOrCreate(
                    ['ip' => $ip],
                    [
                        'risk_level' => $riskLevel,
                        'description' => $description,
                        'url' => $url,
                        'reported_at' => $reportedAt,
                    ]
                );
            }

            $this->info('Indonesia IP reports fetched successfully.');
        } else {
            $this->error('Failed to fetch data from Project Honey Pot.');
        }
    }
}
