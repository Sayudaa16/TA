<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Breach extends Model
{
    protected $table = 'breach';

    protected $fillable = [
        'type',
        'email',
        'status'
    ];
}
