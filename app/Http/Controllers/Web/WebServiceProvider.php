<?php

declare(strict_types=1);

namespace App\Modules\Web;

use Illuminate\Support\ServiceProvider;

class WebServiceProvider extends ServiceProvider
{
    const string NS = 'web';

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $routeFile = __DIR__.'/Routes/web.php';
        if (file_exists($routeFile)) {
            $this->loadRoutesFrom($routeFile);
        }

        $viewsDir = public_path(sprintf('themes/%s/views', config('app.themes')));
        if (is_dir($viewsDir)) {
            $this->loadViewsFrom($viewsDir, self::NS);
        }
    }
}
