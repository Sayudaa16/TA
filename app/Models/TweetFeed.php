<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TweetFeed extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'user', 'type', 'value', 'tags', 'tweet'
    ];

    protected $casts = [
        'tags' => 'array',
        'date' => 'datetime',
    ];
}
