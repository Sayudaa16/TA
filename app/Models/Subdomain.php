<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdomain extends Model
{
    use HasFactory;

    protected $fillable = ['subdomain', 'type', 'value', 'ports', 'last_seen'];

    protected $casts = [
        'ports' => 'array',
        'last_seen' => 'datetime',
    ];
}

