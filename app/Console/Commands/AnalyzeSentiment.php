<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AnalyzeSentiment extends Command
{
    protected $signature = 'analyze:sentiment';
    protected $description = 'Run sentiment analysis on tweets using Python script';

    public function handle()
    {
        $pythonScriptPath = storage_path('scripts/analyze_sentiment.py'); // Pastikan path benar
        $process = new Process(['C:\laragon\bin\python\python-3.10\python.EXE', 'C:\laragon\www\Dasaratha\storage\scripts/analyze_sentiment.py']);
        $process->setTimeout(300); // Ubah timeout jadi 5 menit (300 detik)
        $process->run();


        try {
            $process->mustRun();
            $this->info("Sentiment analysis completed successfully.");
        } catch (ProcessFailedException $exception) {
            Log::error("Sentiment analysis failed: " . $exception->getMessage());
            $this->error("Sentiment analysis failed. Check logs for details.");
        }
    }
}
