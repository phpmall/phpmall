<?php

declare(strict_types=1);

namespace App\Modules\Admin;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    const string NS = 'admin';

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
        $this->commands([
            Commands\GenRouteCommand::class,
        ]);

        $this->loadRoutesFrom(__DIR__.'/Routes/route.php');
        $this->loadTranslationsFrom(__DIR__.'/Languages', self::NS);
        $this->loadViewsFrom(__DIR__.'/Views', self::NS);
    }
}
