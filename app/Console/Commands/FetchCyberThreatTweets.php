<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\CyberThreatTweet;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Symfony\Component\Process\Process;

class FetchCyberThreatTweets extends Command
{
    protected $signature = 'fetch:cyberthreat-tweets';
    protected $description = 'Fetch recent cybersecurity-related tweets and classify them';

    public function handle()
    {
        $query = "ransomware OR LockBit OR WannaCry OR CVE OR exploit OR malware OR cyber attack OR data breach OR phishing OR DDoS OR cybersecurity OR hacking OR zero-day -is:retweet -is:reply";

        $response = Http::withToken(env('TWITTER_BEARER_TOKEN'))
            ->get("https://api.twitter.com/2/tweets/search/recent", [
                'query' => $query,
                'max_results' => 15,
                'tweet.fields' => 'created_at,public_metrics',
                'expansions' => 'author_id',
                'user.fields' => 'username,name'
            ]);

        if ($response->failed()) {
            Log::error("Failed to fetch tweets. Response: " . $response->body());
            $this->error("Failed to fetch tweets.");
            return;
        }

        $data = $response->json();
        $tweets = $data['data'] ?? [];
        $users = collect($data['includes']['users'] ?? []);

        if (!$tweets) {
            $this->info("No tweets found.");
            return;
        }

        foreach ($tweets as $tweet) {
            $user = $users->firstWhere('id', $tweet['author_id']);
            $author_username = $user['username'] ?? 'unknown';

            // Panggil Python script untuk klasifikasi
            $category = $this->classifyTweet($tweet['text']);

            // Debugging output
            Log::info("Tweet: " . $tweet['text']);
            Log::info("Classified Category: " . $category);

            CyberThreatTweet::updateOrCreate(
                ['tweet_id' => $tweet['id']],
                [
                    'author_id' => $tweet['author_id'] ?? null,
                    'author_username' => $author_username,
                    'text' => $tweet['text'],
                    'retweet_count' => $tweet['public_metrics']['retweet_count'] ?? 0,
                    'reply_count' => $tweet['public_metrics']['reply_count'] ?? 0,
                    'like_count' => $tweet['public_metrics']['like_count'] ?? 0,
                    'quote_count' => $tweet['public_metrics']['quote_count'] ?? 0,
                    'bookmark_count' => $tweet['public_metrics']['bookmark_count'] ?? 0,
                    'impression_count' => $tweet['public_metrics']['impression_count'] ?? 0,
                    'category' => $category ?: 'Unknown', // Jika gagal, set ke 'Unknown'
                    'tweet_created_at' => Carbon::parse($tweet['created_at'])
                ]
            );
        }

        $this->info("Cybersecurity tweets successfully fetched and classified.");
    }

    private function classifyTweet($text)
    {
        $process = new Process(['python', base_path('storage/scripts/classify_tweet.py'), $text]);
        $process->run();

        if (!$process->isSuccessful()) {
            Log::error("Python Classification Error: " . $process->getErrorOutput());
            return 'Unknown';
        }

        return trim($process->getOutput());
    }
}
