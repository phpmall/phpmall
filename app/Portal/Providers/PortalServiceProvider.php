<?php

declare(strict_types=1);

namespace App\Portal\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class PortalServiceProvider extends ServiceProvider
{
    public const MODULE = 'portal';

    public function boot(): void
    {
        $modulePath = dirname(__DIR__);

        $migration = $modulePath . '/Migrations';
        if (is_dir($migration)) {
            $this->loadMigrationsFrom($migration);
        }

        $route = $modulePath . '/Routes/web.php';
        if (is_file($route)) {
            $this->loadRoutesFrom($route);
        }

        $view = $modulePath . '/Views';
        if (is_dir($view)) {
            $this->loadViewsFrom($view, Str::camel(self::MODULE));
        }
    }
}
