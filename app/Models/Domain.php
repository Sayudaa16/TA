<?php

namespace App\Models;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable = [
        'domain_name',
        'expiry_date',
        'ssl_expiry_date',
        'registrar_name',
        'created_date',
        'updated_date',
        'name_servers',
        'domain_status',
        'additional_info',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

}
