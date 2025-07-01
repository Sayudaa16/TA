<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MostRecentIpReport extends Model
{
    use HasFactory;

    protected $table = 'mostrecent_ip_reports'; // Pastikan ini sesuai dengan nama tabel di database
    protected $fillable = ['ip', 'risk_level', 'description', 'url', 'reported_at'];
    protected $casts = [
        'reported_at' => 'datetime',
    ];
}
