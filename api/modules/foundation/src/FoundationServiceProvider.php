<?php

declare(strict_types=1);

namespace Juling\Foundation;

use Illuminate\Support\ServiceProvider;

class FoundationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/captcha.php' => config_path('captcha.php'),
        ]);
    }
}
