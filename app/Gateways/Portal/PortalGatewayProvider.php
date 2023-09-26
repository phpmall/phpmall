<?php

declare(strict_types=1);

namespace App\Gateways\Portal;

use Illuminate\Support\ServiceProvider;

class PortalGatewayProvider extends ServiceProvider
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
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/Views', 'portal');

        $this->publishes([
            __DIR__.'/Assets' => public_path('assets/portal'),
        ], 'public');
    }
}
