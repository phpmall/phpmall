<?php

declare(strict_types=1);

namespace App\Bundles\Portal;

use Illuminate\Support\ServiceProvider;

class PortalBundleProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(__DIR__.'/Migrations');
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
    }
}
