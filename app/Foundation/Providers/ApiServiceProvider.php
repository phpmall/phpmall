<?php

declare(strict_types=1);

namespace App\Foundation\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        foreach ($this->getDirs() as $dir) {
            $module = basename($dir);

            $migration = $dir . 'Migrations';
            if (is_dir($migration)) {
                $this->loadMigrationsFrom($migration);
            }

            $route = $dir . 'Routes/api.php';
            if (is_file($route)) {
                $this->loadRoutesFrom($route);
            }

            $view = $dir . 'Views';
            if (is_dir($view)) {
                $this->loadViewsFrom($view, Str::camel($module));
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
