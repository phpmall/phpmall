<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class FiddleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        foreach ($this->getDirs() as $dir) {
            $module = basename($dir);

            $asset = $dir.'Assets';
            if (is_dir($asset)) {
                $this->publishes([
                    $asset => public_path('assets/'.$module),
                ], 'public');
            }

            $migration = $dir.'Migrations';
            if (is_dir($migration)) {
                $this->loadMigrationsFrom($migration);
            }

            $route = $dir.'Routes/api.php';
            if (is_file($route)) {
                $this->loadRoutesFrom($route);
            }

            $route = $dir.'Routes/web.php';
            if (is_file($route)) {
                $this->loadRoutesFrom($route);
            }

            $view = $dir.'Views';
            if (is_dir($view)) {
                $this->loadViewsFrom($view, Str::camel($module));
            }
        }
    }

    private function getDirs(): array
    {
        return glob(app_path('*/'), GLOB_ONLYDIR);
    }
}
