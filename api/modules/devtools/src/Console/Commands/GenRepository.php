<?php

declare(strict_types=1);

namespace Juling\DevTools\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Juling\DevTools\Support\SchemaTrait;

class GenRepository extends Command
{
    use SchemaTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:dao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate repository classes';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $tables = $this->getTables();
        foreach ($tables as $table) {
            $this->repositoryTpl($table['name']);
        }
    }

    private function repositoryTpl(string $tableName): void
    {
        $className = Str::studly(Str::singular($tableName));

        $content = file_get_contents(__DIR__.'/stubs/repository/repository.stub');
        $content = str_replace([
            '{$name}',
            '{$tableName}',
        ], [
            $className,
            $tableName,
        ], $content);

        file_put_contents(app_path('Repositories/'.$className.'Repository.php'), $content);
    }
}
