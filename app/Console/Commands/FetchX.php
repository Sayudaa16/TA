<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Tweet;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FetchX extends Command
{
    protected $signature = 'fetch:x';
    protected $description = 'Fetch latest tweets mentioning BankBCA and store them in the database';

    public function handle()
    {
        $response = Http::withToken(env('TWITTER_BEARER_TOKEN'))
            ->get("https://api.twitter.com/2/tweets/search/recent", [
                'query' => 'BankBCA -is:reply -is:retweet',
                'max_results' => 30,
                'tweet.fields' => 'created_at,public_metrics',
                'expansions' => 'author_id',
                'user.fields' => 'username,name' // Ambil username dari user
            ]);

        if ($response->failed()) {
            Log::error("Failed to fetch tweets. Response: " . $response->body());
            $this->error("Failed to fetch tweets.");
            return;
        }

        $data = $response->json();
        $tweets = $data['data'] ?? [];
        $users = collect($data['includes']['users'] ?? []); // Kumpulkan daftar user

        foreach ($tweets as $tweet) {
            // Ambil username berdasarkan author_id
            $user = $users->firstWhere('id', $tweet['author_id']);
            $author_username = $user['username'] ?? 'unknown'; // Jika tidak ada, beri default

            Tweet::updateOrCreate(
                ['tweet_id' => $tweet['id']],
                [
                    'author_id' => $tweet['author_id'] ?? null,
                    'author_username' => $author_username, // Simpan username dengan benar
                    'text' => $tweet['text'],
                    'retweet_count' => $tweet['public_metrics']['retweet_count'] ?? 0,
                    'reply_count' => $tweet['public_metrics']['reply_count'] ?? 0,
                    'like_count' => $tweet['public_metrics']['like_count'] ?? 0,
                    'quote_count' => $tweet['public_metrics']['quote_count'] ?? 0,
                    'bookmark_count' => $tweet['public_metrics']['bookmark_count'] ?? 0,
                    'impression_count' => $tweet['public_metrics']['impression_count'] ?? 0,
                    'created_at' => Carbon::parse($tweet['created_at'])->format('Y-m-d H:i:s')
                ]
            );
        }

        $this->info("Tweets successfully stored.");
    }
}
