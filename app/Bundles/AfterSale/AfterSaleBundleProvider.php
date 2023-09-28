<?php

declare(strict_types=1);

namespace App\Bundles\AfterSale;

use Illuminate\Support\ServiceProvider;

class AfterSaleBundleProvider extends ServiceProvider
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
