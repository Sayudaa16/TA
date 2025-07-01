<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


// Penjadwalan tugas custom
app(Schedule::class)->command('shodan:check-new-ports')->everyFiveMinutes();
app(Schedule::class)->command('domains:update')->daily();
app(Schedule::class)->command('crawl:cvedata')->everyFiveMinutes();
app(Schedule::class)->command('crawl:cve')->everyFiveMinutes();
app(Schedule::class)->command('hackernews:fetch')->everyMinute();
app(Schedule::class)->command('fetch:x')->everyFifteenMinutes();
app(Schedule::class)->command('shodan:fetch-subdomains')->everyFifteenMinutes();
app(Schedule::class)->command('shodan:fetch-alerts')->daily();

// Penjadwalan untuk scanning domain pada jam 17:00
app(Schedule::class)->call(function () {
    $domain = 'obf.id'; // Ganti dengan domain yang ingin dipindai
    app(\App\Http\Controllers\DeHashedController::class)->search($domain); // Memanggil fungsi search
})->dailyAt('23:00');

