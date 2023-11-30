<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class BundleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $dirs = array_merge(
            glob(base_path('app/Api/*/')),
            glob(base_path('app/Bundles/*/'))
        );

        foreach ($dirs as $dir) {
            $migration = $dir.'Migrations';
            if (is_dir($migration)) {
                $this->loadMigrationsFrom($migration);
            }

            $route = $dir.'Routes/web.php';
            if (is_file($route)) {
                $this->loadRoutesFrom($route);
            }

            $view = $dir.'Views';
            if (is_dir($view)) {
                $provider = basename($dir);
                $this->loadViewsFrom($view, Str::camel($provider));
            }
        }
    }
}
