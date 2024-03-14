<?php

declare(strict_types=1);

namespace App\Foundation\DevTools\Console\Commands;

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

        $fs->ensureDirectoryExists(storage_path('app/openapi'));
        $fs->ensureDirectoryExists(storage_path('app/ts/services'));
        $fs->ensureDirectoryExists(storage_path('app/ts/types'));

        $root = dirname(__DIR__, 3);
        $fs->copyDirectory($root.'/stubs/app', base_path('app'));
        $fs->copyDirectory($root.'/stubs/docker', base_path('docker'));
        $fs->copyDirectory($root.'/stubs/docs', base_path('docs'));
        $fs->copyDirectory($root.'/stubs/scripts', base_path('scripts'));
    }
}
