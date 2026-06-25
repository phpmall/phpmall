<?php

declare(strict_types=1);

namespace App\Modules\Store\Providers;

use Illuminate\Support\ServiceProvider;

class StoreServiceProvider extends ServiceProvider
{
    /**
     * Register any module services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any module services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'store');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }
}
