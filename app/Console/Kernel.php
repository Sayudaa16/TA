<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Daftar command yang didaftarkan di sini, seperti:
        // \App\Console\Commands\CheckServiceUpdates::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('domains:update')->daily(); // Menjadwalkan pembaruan domain tiap hari
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        // Memuat route untuk command artisan
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
