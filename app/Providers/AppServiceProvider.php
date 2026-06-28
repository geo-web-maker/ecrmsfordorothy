<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.navigation', function () {
            if ($user = auth()->user()) {
                $user->loadMissing(['whistleblowerProfile', 'officerProfile', 'adminProfile']);
            }
            if (config('app.env') === 'production') {
                \URL::forceScheme('https');
            }
        });

        $verifySsl = filter_var(config('services.http.verify_ssl', true), FILTER_VALIDATE_BOOLEAN);
        $caBundle  = config('services.http.ca_bundle');

        if (! $verifySsl) {
            Http::globalOptions(['verify' => false]);
        } elseif (is_string($caBundle) && is_file($caBundle)) {
            Http::globalOptions(['verify' => $caBundle]);
        }
    }
}
