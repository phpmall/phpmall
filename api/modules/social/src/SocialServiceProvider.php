<?php

namespace Juling\Social;

use Illuminate\Support\ServiceProvider;
use Overtrue\Socialite\SocialiteManager;

class SocialServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(SocialiteManager::class, function () {
            $config = array_merge(\config('socialite', []), \config('services.socialite', []));

            return new SocialiteManager($config);
        });
    }

    public function provides()
    {
        return [SocialiteManager::class];
    }
}
