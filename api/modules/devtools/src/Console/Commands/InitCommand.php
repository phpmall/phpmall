<?php

declare(strict_types=1);

namespace Juling\DevTools\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'DevTools init';

    public function handle(): void
    {
        $fs = new Filesystem();

        $fs->ensureDirectoryExists(app_path('Bundles'));
        $fs->ensureDirectoryExists(app_path('Entities'));
        $fs->ensureDirectoryExists(app_path('Enums'));
        $fs->ensureDirectoryExists(app_path('Repositories'));
        $fs->ensureDirectoryExists(app_path('Services'));

        $fs->ensureDirectoryExists(storage_path('app/openapi'));
        $fs->ensureDirectoryExists(storage_path('app/ts/services'));
        $fs->ensureDirectoryExists(storage_path('app/ts/types'));
    }
}
