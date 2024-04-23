<?php

declare(strict_types=1);

namespace Juling\DevTools;

use Illuminate\Support\ServiceProvider;
use Juling\DevTools\Console\Commands\GenController;
use Juling\DevTools\Console\Commands\GenDict;
use Juling\DevTools\Console\Commands\GenEntity;
use Juling\DevTools\Console\Commands\GenModel;
use Juling\DevTools\Console\Commands\GenRepository;
use Juling\DevTools\Console\Commands\GenRoute;
use Juling\DevTools\Console\Commands\GenService;
use Juling\DevTools\Console\Commands\GenTypescript;
use Juling\DevTools\Console\Commands\InitCommand;

class DevToolsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenController::class,
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
