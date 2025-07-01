<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WhoisAPIService;
use App\Models\Domain;

class UpdateDomainData extends Command
{
    protected $signature = 'domains:update';
    protected $description = 'Update domain data';

    public function handle()
    {
        $whoisService = new WhoisAPIService();
        $domains = Domain::all();

        foreach ($domains as $domain) {
            $domainData = $whoisService->fetchDomainData($domain->domain_name);
            if ($domainData) {
                $domain->update([
                    'expiry_date' => $domainData['WhoisRecord']['registryData']['expiresDate'],
                ]);
            }
        }

        $this->info('Domain data updated successfully.');
    }
}
