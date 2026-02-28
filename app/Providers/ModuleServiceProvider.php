<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ModuleServiceProvider extends ServiceProvider
{
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
        $providers = glob(app_path('Modules/*/*Provider.php'));
        foreach ($providers as $provider) {
            $provider = str_replace('\\', '/', $provider);
            preg_match('/(app\/\w+\/\w+\/\w+Provider)\.php/', $provider, $matches);
            if (isset($matches[1])) {
                $provider = str_replace('/', '\\', $matches[1]);
                $this->app->register(Str::studly($provider));
            }
        }
    }
}
