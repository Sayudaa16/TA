<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearLoginLogs extends Command
{
    protected $signature = 'logs:clear';
    protected $description = 'Hapus log login yang lebih lama dari 24 jam';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        DB::table('login_logs')
            ->where('logged_in_at', '<', now()->subDay()) // Lebih lama dari 24 jam
            ->delete();

        $this->info('Log login lebih lama dari 24 jam telah dihapus.');
    }
}
