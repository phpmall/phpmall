<?php

declare(strict_types=1);

namespace App\Api\Seller;

use Illuminate\Support\ServiceProvider;

class SellerGatewayProvider extends ServiceProvider
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
    }
}
