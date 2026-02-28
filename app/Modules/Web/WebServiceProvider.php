<?php

declare(strict_types=1);

namespace App\Modules\Web;

use Illuminate\Support\ServiceProvider;

class WebServiceProvider extends ServiceProvider
{
    const string NS = 'web';

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

        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
        $this->loadTranslationsFrom(__DIR__.'/Languages', self::NS);
        $this->loadViewsFrom(public_path(sprintf('themes/%s/views', config('app.themes'))), self::NS);
    }
}
