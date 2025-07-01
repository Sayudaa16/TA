<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandMention extends Model
{
    use HasFactory;

    protected $fillable = ['platform', 'url'];  // Pastikan 'platform' dan 'url' ada di sini
}
