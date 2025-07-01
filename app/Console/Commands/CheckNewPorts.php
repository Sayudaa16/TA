<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ShodanHost;
use App\Models\Port;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewPortNotification;
use GuzzleHttp\Client;

class CheckNewPorts extends Command
{
    protected $signature = 'shodan:check-new-ports';
    protected $description = 'Check for new ports on registered IPs and send notifications.';

    protected $shodanApiKey;
    protected $client;

    public function __construct()
    {
        parent::__construct();
        $this->shodanApiKey = env('SHODAN_API_KEY');
        $this->client = new Client();
    }

    public function handle()
    {
        $hosts = ShodanHost::all();

        foreach ($hosts as $host) {
            try {
                $response = $this->client->get("https://api.shodan.io/shodan/host/{$host->ip}?key={$this->shodanApiKey}");
                $shodanData = json_decode($response->getBody(), true);

                if ($shodanData && isset($shodanData['ports'])) {
                    $newPorts = $shodanData['ports'];
                    foreach ($newPorts as $portNumber) {
                        $existingPort = Port::where('shodan_host_id', $host->id)
                                           ->where('port_number', $portNumber)
                                           ->first();

                        if (!$existingPort) {
                            // Simpan port baru
                            $port = $host->ports()->create([
                                'port_number' => $portNumber,
                                'asset_group' => 'Unknown',
                                'trigger' => 'New port detected',
                                'version' => 'Unknown',
                                'details' => json_encode(['port' => $portNumber]),
                            ]);

                            // Kirim email notifikasi
                            Mail::to($host->email)->send(new NewPortNotification($port));

                            $this->info("Email notification sent for IP: {$host->ip}, Port: {$portNumber}");
                        }
                    }
                }
            } catch (\Exception $e) {
                $this->error("Failed to process IP {$host->ip}: " . $e->getMessage());
            }
        }

        return 0;
    }
}
