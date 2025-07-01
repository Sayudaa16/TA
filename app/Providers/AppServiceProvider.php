<?php

namespace App\Providers;


use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Listeners\LogSuccessfulLogin;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
       // Daftarkan service ke container Laravel
       $this->app->singleton(ShodanService::class, function ($app) {
            return new ShodanService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Event::listen(
        //     Login::class,
        //     [LogSuccessfulLogin::class, 'handle']
        // );
    }
}
