<?php

declare(strict_types=1);

namespace Juling\Portal;

use Illuminate\Support\ServiceProvider;
use Juling\Portal\Commands\GenRoute;

class PortalServiceProvider extends ServiceProvider
{
    const MODULE = 'portal';

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/Views', self::MODULE);

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenRoute::class,
            ]);
        }
    }
}
