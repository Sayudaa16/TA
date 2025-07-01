<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class OrganizationFilter
{
    public function handle($request, Closure $next)
    {
        // Ambil organization_id dari session
        if ($organizationId = Session::get('organization_id')) {
            // Simpan di singleton agar bisa diakses global
            app()->singleton('organization_id', fn () => $organizationId);
        }

        return $next($request);
    }
}

