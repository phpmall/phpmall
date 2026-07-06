<?php

declare(strict_types=1);

namespace App\Modules\Payment\Providers;

use App\Modules\Payment\Services\MockPaymentGateway;
use App\Modules\Payment\Services\PaymentGatewayInterface;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register any module services.
     */
    public function register(): void
    {
        $this->app->bind(
            PaymentGatewayInterface::class,
            MockPaymentGateway::class
        );
    }

    /**
     * Bootstrap any module services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'payment');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }
}
