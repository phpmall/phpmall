<?php

declare(strict_types=1);

namespace Juling\Auth;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
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
            dirname(__DIR__).'/config/config.php' => config_path('jwt.php'),
        ]);
    }
}
