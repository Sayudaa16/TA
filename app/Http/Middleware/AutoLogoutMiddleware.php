<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AutoLogoutMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            // Cek waktu terakhir aktif
            $lastActivity = Session::get('lastActivityTime');
            $currentTime = now();

            if ($lastActivity && $currentTime->diffInMinutes($lastActivity) >= config('session.lifetime')) {
                // Logout pengguna jika melebihi waktu yang diatur
                Auth::logout();
                Session::flush();
                return redirect()->route('login.index')->withErrors(['message' => 'Anda telah logout karena tidak ada aktivitas.']);
            }

            // Update waktu aktivitas terakhir
            Session::put('lastActivityTime', $currentTime);
        }

        return $next($request);
    }
}
