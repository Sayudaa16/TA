<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scan extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip',
        'os',
        'isp',
        'org',
        'ports',
        'vulns',
    ];
}
