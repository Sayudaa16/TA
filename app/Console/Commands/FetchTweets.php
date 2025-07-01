<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\TweetFeed;

class FetchTweets extends Command
{
    protected $signature = 'tweet:fetch';
    protected $description = 'Fetch new tweets from multiple APIs and store in database';

    public function handle()
    {
        $apiUrls = [
            'today' => 'https://api.tweetfeed.live/v1/today',
            'phishing-url' => 'https://api.tweetfeed.live/v1/today/phishing/url',
            'cobaltstrike-ip' => 'https://api.tweetfeed.live/v1/week/cobaltstrike/ip',
            'malwrhunter-sha256' => 'https://api.tweetfeed.live/v1/month/@malwrhunterteam/sha256',
        ];

        foreach ($apiUrls as $category => $apiUrl) {
            $response = Http::get($apiUrl);
            $tweets = $response->json();

            foreach ($tweets as $tweet) {
                TweetFeed::updateOrCreate(
                    ['tweet' => $tweet['tweet'] ?? $tweet['value']], // Hindari duplikasi berdasarkan tweet/value
                    [
                        'date' => $tweet['date'] ?? now(),
                        'user' => $tweet['user'] ?? 'Unknown',
                        'type' => $tweet['type'] ?? $category, // Gunakan kategori sebagai fallback
                        'value' => $tweet['value'] ?? 'N/A',
                        'tags' => $tweet['tags'] ?? [],
                    ]
                );
            }
        }

        $this->info('All tweet feeds updated successfully!');
    }
}
