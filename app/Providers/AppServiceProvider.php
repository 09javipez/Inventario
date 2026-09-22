<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\KardexService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //Servicio de fachade en kardex
        $this->app->bind('kardex', function () {
            return new KardexService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
