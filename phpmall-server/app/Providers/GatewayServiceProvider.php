<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GatewayServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $gateways = glob(app_path('Gateways/*/*ServiceProvider.php'));

        foreach ($gateways as $provider) {
            preg_match('/(Gateways\/\w+\/\w+ServiceProvider)/', $provider, $matches);
            $provider = str_replace('/', '\\', $matches[1]);
            $this->app->register('App\\'.$provider);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
