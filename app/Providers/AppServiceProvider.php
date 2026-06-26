<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public const string NS = 'web';

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
        $this->loadProviders();
        $this->loadThemes();
    }

    private function loadProviders(): void
    {
        $providers = glob(app_path('Modules/*/*Provider.php'));
        foreach ($providers as $provider) {
            $provider = \str_replace('\\', '/', $provider);
            preg_match('/(app\/\w+\/\w+\/\w+Provider)\.php/', $provider, $matches);
            if (isset($matches[1])) {
                $provider = \str_replace('/', '\\', $matches[1]);
                $this->app->register(Str::studly($provider));
            }
        }
    }

    private function loadThemes(): void
    {
        $viewsDir = public_path(\sprintf('themes/%s/views', config('app.themes', 'default')));
        if (is_dir($viewsDir)) {
            $this->loadViewsFrom($viewsDir, self::NS);
        }
    }
}
