<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Subdomain;

class FetchShodanSubdomains extends Command
{
    protected $signature = 'shodan:fetch-subdomains';
    protected $description = 'Fetch subdomains from Shodan and store in database';

    public function handle()
    {
        $apiKey = 'gGGrHdtEZ1RaoYwggs4VhYvDuYBw7h3t';
        $domainName = 'iti.ac.id';
        $apiUrl = "https://api.shodan.io/dns/domain/{$domainName}?key={$apiKey}";

        $response = Http::get($apiUrl);

        if ($response->successful()) {
            $shodanData = $response->json();

            foreach ($shodanData['data'] ?? [] as $record) {
                if (!empty($record['subdomain'])) {
                    Subdomain::updateOrCreate(
                        [
                            'subdomain' => "{$record['subdomain']}.{$domainName}",
                            'type' => $record['type'],
                            'value' => $record['value']
                        ],
                        [
                            'ports' => $record['ports'] ?? [],
                            'last_seen' => $record['last_seen'] ?? now(),
                        ]
                    );
                }
            }

            $this->info('Subdomains fetched and updated successfully.');
        } else {
            $this->error('Failed to fetch data from Shodan.');
        }
    }
}
