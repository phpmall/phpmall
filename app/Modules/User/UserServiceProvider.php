<?php

declare(strict_types=1);

namespace App\Modules\User;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    const string NS = 'user';

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
        $this->loadViewsFrom(__DIR__.'/Views', self::NS);
    }
}
