<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CyberSecurityNews extends Model
{
    use HasFactory;

    protected $table = 'cyber_security_news';

    protected $fillable = [
        'title',
        'description',
        'url',
        'url_to_image',
        'published_at',
        'category',
    ];
}
