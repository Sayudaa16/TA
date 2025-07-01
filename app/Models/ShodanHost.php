<?php
// app/Models/ShodanHost.php

namespace App\Models;

use App\Models\Port;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShodanHost extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip', 'hostnames', 'country', 'city', 'ports', 'vulns',
        'isp', 'domains', 'organization', 'asn', 'service_banners',
        'email', 'latitude', 'longitude', 'organization_id', // Tambahkan di sini
    ];

    protected $casts = [
        'ports' => 'array',
        'vulns' => 'array',
        'domains' => 'array',
        'organization' => 'array',
        'hostnames' => 'array',
        'service_banners' => 'array', // Tambahkan casting ke array
        'latitude' => 'float', // Casting latitude ke tipe float
        'longitude' => 'float', // Casting longitude ke tipe float
    ];

    public function ports()
    {
        return $this->hasMany(Port::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
