<?php

declare(strict_types=1);

namespace Juling\DevTools\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Juling\DevTools\Support\SchemaTrait;

class GenModel extends Command
{
    use SchemaTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate model classes';

    private array $ignoreColumns = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $tables = $this->getTables();
        foreach ($tables as $table) {
            $this->modelTpl($table['name']);
        }
    }

    private function modelTpl(string $tableName): void
    {
        $className = Str::studly(Str::singular($tableName));
        $columns = $this->getTableColumns($tableName);

        $softDelete = false;

        $fieldStr = '';
        foreach ($columns as $column) {
            if (! in_array($column['name'], $this->ignoreColumns)) {
                $fieldStr .= str_pad(' ', 8)."'{$column['name']}',\n";
            }
            if ($column['name'] === 'deleted_at') {
                $softDelete = true;
            }
        }
        $fieldStr = rtrim($fieldStr, "\n");

        $useSoftDelete = '';
        if ($softDelete) {
            $useSoftDelete = "    use SoftDeletes;\n";
        }

        $content = file_get_contents(__DIR__.'/stubs/model/model.stub');
        $content = str_replace([
            '{$name}',
            '$tableName',
            '$useSoftDelete',
            '$fieldStr',
        ], [
            $className,
            $tableName,
            $useSoftDelete,
            $fieldStr,
        ], $content);

        file_put_contents(app_path('Models/'.$className.'.php'), $content);
    }
}
