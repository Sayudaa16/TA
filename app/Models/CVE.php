<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CVE extends Model
{
    use HasFactory;

    protected $fillable = ['cve_id', 'description', 'created_at_api', 'updated_at_api'];

    protected $casts = [
        'created_at_api' => 'datetime',
        'updated_at_api' => 'datetime',
    ];
}
