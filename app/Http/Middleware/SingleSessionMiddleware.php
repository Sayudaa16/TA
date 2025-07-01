<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SingleSessionMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $sessionId = session()->getId();

            // Cek sesi lain untuk user ini
            $existingSession = DB::table('sessions')
                ->where('user_id', $userId)
                ->where('id', '!=', $sessionId)
                ->first();

                if ($existingSession) {
                    DB::table('sessions')
                        ->where('id', $existingSession->id)
                        ->delete();

                    // Logout pengguna saat ini dan beri pesan
                    Auth::logout();
                    return redirect()->route('login.index')->withErrors([
                        'message' => 'Sesi Anda telah diakhiri karena login dari perangkat lain.',
                    ]);
                }


            // Simpan user_id ke sesi sekarang
            DB::table('sessions')
                ->where('id', $sessionId)
                ->update(['user_id' => $userId]);
        }

        return $next($request);
    }
}
