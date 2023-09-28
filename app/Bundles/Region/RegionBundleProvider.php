<?php

declare(strict_types=1);

namespace App\Bundles\Region;

use Illuminate\Support\ServiceProvider;

class RegionBundleProvider extends ServiceProvider
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
    }
}
