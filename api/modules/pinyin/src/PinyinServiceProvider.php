<?php

namespace Juling\Pinyin;

use Illuminate\Support\ServiceProvider;
use Overtrue\Pinyin\Pinyin;

class PinyinServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the provider.
     */
    public function register()
    {
        $this->app->singleton(Pinyin::class, function ($app) {
            return new Pinyin();
        });

        $this->app->alias(Pinyin::class, 'pinyin');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Pinyin::class, 'pinyin'];
    }
}
