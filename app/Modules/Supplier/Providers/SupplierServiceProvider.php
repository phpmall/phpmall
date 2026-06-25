<?php

declare(strict_types=1);

namespace App\Modules\Supplier\Providers;

use Illuminate\Support\ServiceProvider;

class SupplierServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'supplier');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }
}
