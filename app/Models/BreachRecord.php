<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BreachRecord extends Model
{
    protected $fillable = [
        'Name',
        'Title',
        'Description',
        'BreachDate',
        'PwnCount',
        'Domain',
        'LogoPath',
        'IsVerified',
    ];
}
