<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CrawlCVEData;

class CrawlCVECommand extends Command
{
    protected $signature = 'crawl:cve';
    protected $description = 'Crawl CVE data from OpenCVE and store it in the database.';

    public function handle()
    {
        dispatch(new CrawlCVEData());
        $this->info('CVE Crawling Job has been dispatched.');
    }
}
