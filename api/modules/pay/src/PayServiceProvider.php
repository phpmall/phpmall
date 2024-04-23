<?php

declare(strict_types=1);

namespace Juling\Pay;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Yansongda\Pay\Pay;

class PayServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Boot the service.
     */
    public function boot()
    {
        if ($this->app instanceof Application && $this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/pay.php' => config_path('pay.php'), ],
                'laravel-pay'
            );
        }
    }

    /**
     * Register the service.
     *
     * @throws \Yansongda\Pay\Exception\ContainerException
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/pay.php', 'pay');

        Pay::config(config('pay'));

        $this->app->singleton('pay.alipay', function () {
            return Pay::alipay();
        });

        $this->app->singleton('pay.wechat', function () {
            return Pay::wechat();
        });

        $this->app->singleton('pay.unipay', function () {
            return Pay::unipay();
        });
    }

    /**
     * Get services.
     */
    public function provides(): array
    {
        return ['pay.alipay', 'pay.wechat', 'pay.unipay'];
    }
}
