<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

class LogSuccessfulLogin
{
    public function __construct()
    {
        //
    }

    public function handle(Login $event)
    {
        $user = $event->user;
        $ipAddress = request()->ip();
        $timestamp = now();

        // Cek apakah sudah ada log untuk user ini dalam waktu tertentu
        $exists = \DB::table('login_logs')
            ->where('user_id', $user->id)
            ->where('ip_address', $ipAddress)
            ->where('logged_in_at', '>=', now()->subMinutes(5)) // Cek dalam 5 menit terakhir
            ->exists();

        if (!$exists) {
            // Simpan log ke file log Laravel
            Log::info("User {$user->id} logged in at {$timestamp} from IP: {$ipAddress}");

            // Simpan ke database
            \DB::table('login_logs')->insert([
                'user_id' => $user->id,
                'ip_address' => $ipAddress,
                'logged_in_at' => $timestamp,
            ]);
        }
    }
}
