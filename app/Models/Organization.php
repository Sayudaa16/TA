<?php
namespace App\Models;

use App\Models\Port;
use App\Models\User;
use App\Models\Domain;
use App\Models\ShodanHost;
use App\Models\Vulnerability;
use App\Models\DehashedResult;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    public function ports()
    {
        return $this->hasMany(Port::class);
    }

    public function vulnerabilities()
    {
        return $this->hasMany(Vulnerability::class);
    }

    public function dehashed()
    {
        return $this->hasMany(DehashedResult::class);
    }

    public function shodanHosts()
    {
        return $this->hasMany(ShodanHost::class);
    }

}
