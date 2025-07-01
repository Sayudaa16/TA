<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndonesiaIpReport extends Model
{
    use HasFactory;

    protected $table = 'indonesia_ip_reports';
    protected $fillable = ['ip', 'risk_level', 'description', 'url', 'reported_at'];
    protected $casts = [
        'reported_at' => 'datetime',
    ];
}
