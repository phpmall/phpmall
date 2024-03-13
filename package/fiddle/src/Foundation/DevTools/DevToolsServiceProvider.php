<?php

declare(strict_types=1);

namespace App\Foundation\DevTools;

use Illuminate\Support\ServiceProvider;
use App\Foundation\DevTools\Console\Commands\GenDict;
use App\Foundation\DevTools\Console\Commands\GenEntity;
use App\Foundation\DevTools\Console\Commands\GenModel;
use App\Foundation\DevTools\Console\Commands\GenRepository;
use App\Foundation\DevTools\Console\Commands\GenRoute;
use App\Foundation\DevTools\Console\Commands\GenService;
use App\Foundation\DevTools\Console\Commands\GenTypescript;
use App\Foundation\DevTools\Console\Commands\InitCommand;

class DevToolsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenDict::class,
                GenEntity::class,
                GenModel::class,
                GenRepository::class,
                GenRoute::class,
                GenService::class,
                GenTypescript::class,
                InitCommand::class,
            ]);
        }
    }
}
