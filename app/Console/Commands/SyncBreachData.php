<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\BreachRecord;

class SyncBreachData extends Command
{
    protected $signature = 'sync:breaches';
    protected $description = 'Sync data breach dari HaveIBeenPwned API ke database';

    public function handle()
    {
        $this->info('Syncing breach data...');

        $response = Http::get('https://haveibeenpwned.com/api/v3/breaches');

        if ($response->successful()) {
            $breaches = collect($response->json());

            $breaches->each(function ($item) {
                BreachRecord::updateOrCreate(
                    ['Name' => $item['Name']],
                    [
                        'Title' => $item['Title'],
                        'Description' => $item['Description'],
                        'BreachDate' => $item['BreachDate'],
                        'PwnCount' => $item['PwnCount'],
                        'Domain' => $item['Domain'],
                        'LogoPath' => $item['LogoPath'],
                        'IsVerified' => $item['IsVerified'],
                    ]
                );
            });

            $this->info('Sync completed!');
        } else {
            $this->error('Gagal mengambil data dari API.');
        }
    }
}
