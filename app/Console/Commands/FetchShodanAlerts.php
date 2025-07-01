<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ShodanHost;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\ShodanAlertNotification;

class FetchShodanAlerts extends Command
{
    protected $signature = 'shodan:fetch-alerts';
    protected $description = 'Fetch alerts from Shodan API and send notifications to associated emails.';

    public function handle()
    {
        $shodanApiKey = env('SHODAN_API_KEY');
        $client = new Client();

        try {
            // Ambil data alert dari Shodan
            $response = $client->get("https://api.shodan.io/shodan/alert/info?key={$shodanApiKey}");
            $alerts = json_decode($response->getBody(), true);

            foreach ($alerts as $alert) {
                $ip = $alert['filters']['ip'] ?? null;

                if ($ip) {
                    // Cari IP di database
                    $host = ShodanHost::where('ip', $ip)->first();

                    if ($host && $host->email) {
                        // Kirim email ke user terkait
                        Mail::to($host->email)->send(new ShodanAlertNotification($alert));
                        $this->info("Alert sent to {$host->email} for IP: {$ip}");
                    }
                }
            }
        } catch (\Exception $e) {
            $this->error("Failed to fetch alerts: " . $e->getMessage());
        }
    }
}
