<?php

declare(strict_types=1);

namespace App\Foundation\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class BundleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        foreach ($this->getDirs() as $dir) {
            $module = basename($dir);
            $migration = $dir . 'Migrations';
            if (is_dir($migration)) {
                $this->loadMigrationsFrom($migration);
            }

            $route = $dir . 'Routes/web.php';
            if (is_file($route)) {
                $this->loadRoutesFrom($route);
            }

            $view = $dir . 'Views';
            if (is_dir($view)) {
                $provider = basename($dir);
                $this->loadViewsFrom($view, Str::camel($provider));
            }

            $asset = $dir . 'Assets';
            if (is_dir($view)) {
                $this->publishes([
                    $asset => public_path('assets/' . $module),
                ], 'public');
            }
        }
    }

    private function getDirs(): array
    {
        return array_merge(
            glob(app_path('Api/*/')),
            glob(app_path('Bundles/*/'))
        );
    }
}
