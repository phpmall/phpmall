<?php

declare(strict_types=1);

namespace Juling\DevTools\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Juling\DevTools\Support\SchemaTrait;

class GenService extends Command
{
    use SchemaTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate service classes';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $tables = $this->getTables();
        foreach ($tables as $table) {
            $this->serviceTpl($table['name']);
        }
    }

    private function serviceTpl(string $tableName): void
    {
        $className = Str::studly(Str::singular($tableName));

        $content = file_get_contents(__DIR__.'/stubs/service/service.stub');
        $content = str_replace([
            '{$name}',
        ], [
            $className,
        ], $content);

        $serviceFile = app_path('Services/'.$className.'Service.php');
        if (! file_exists($serviceFile)) {
            file_put_contents($serviceFile, $content);
        }
    }
}
