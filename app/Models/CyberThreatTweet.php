<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CyberThreatTweet extends Model
{
    use HasFactory;

    protected $table = 'cyber_threat_tweets';

    protected $fillable = [
        'tweet_id', 'author_id', 'author_username', 'text',
        'retweet_count', 'reply_count', 'like_count', 'quote_count',
        'bookmark_count', 'impression_count', 'category', 'tweet_created_at'
    ];

    protected $casts = [
        'tweet_created_at' => 'datetime',
    ];
}
