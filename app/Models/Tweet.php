<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;

    protected $fillable = [
        'tweet_id', 'author_id', 'text', 'retweet_count', 'reply_count', 'author_username',
        'like_count', 'quote_count', 'bookmark_count', 'impression_count', 'created_at', 'category'
    ];


    public $timestamps = false;
}
