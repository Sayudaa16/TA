<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HackerNews extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'url', 'url_to_image', 'published_at', 'category',
    ];
}

