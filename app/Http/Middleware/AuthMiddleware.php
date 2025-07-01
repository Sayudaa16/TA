<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
         // Periksa apakah pengguna telah login
         if (!Auth::check()) {
            // Jika tidak login, arahkan ke halaman login
            return redirect()->route('login.index');
        }

        return $next($request);
    }
}
