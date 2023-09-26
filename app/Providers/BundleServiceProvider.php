<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class BundleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $gateways = array_merge(
            glob(base_path('app/Gateways/*/*GatewayProvider.php')),
            glob(base_path('app/Bundles/*/*BundleProvider.php'))
        );

        foreach ($gateways as $provider) {
            preg_match('/(app\/\w+\/\w+\/\w+Provider)/', $provider, $matches);
            if (isset($matches[1])) {
                $provider = str_replace('/', '\\', $matches[1]);
                $this->app->register(Str::studly($provider));
            }
        }
    }
}
