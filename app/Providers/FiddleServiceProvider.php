<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class FiddleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $infrastructures = glob(dirname(__DIR__).'/Foundation/*/*ServiceProvider.php');
        foreach ($infrastructures as $infra) {
            $serviceProvider = Str::substr($infra, strrpos($infra, 'src/Foundation/') + 3, -4);
            $this->app->register('App'.Str::replace('/', '\\', $serviceProvider));
        };

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
        return array_merge(
            glob(app_path('Api/*'), GLOB_ONLYDIR),
            glob(app_path('Bundles/*'), GLOB_ONLYDIR)
        );
    }
}
