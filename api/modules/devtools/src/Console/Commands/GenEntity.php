<?php

declare(strict_types=1);

namespace Juling\DevTools\Console\Commands;

use Binafy\LaravelStub\Facades\LaravelStub;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Juling\DevTools\Support\SchemaTrait;

class GenEntity extends Command
{
    use SchemaTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:entity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate entity classes';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $tables = $this->getTables();
        foreach ($tables as $table) {
            $this->entityTpl($table['name']);
        }
    }

    private function entityTpl(string $tableName): void
    {
        $className = Str::studly(Str::singular($tableName));
        $columns = $this->getTableColumns($tableName);

        $fields = "\n";
        foreach ($columns as $column) {
            if ($column['name'] === 'default') {
                $column['name'] = 'isDefault';
            }
            if ($column['name'] === 'id' && empty($column['comment'])) {
                $column['comment'] = 'ID';
            }
            if ($column['name'] === 'created_at' && empty($column['comment'])) {
                $column['comment'] = '创建时间';
            }
            if ($column['name'] === 'updated_at' && empty($column['comment'])) {
                $column['comment'] = '更新时间';
            }
            if ($column['name'] === 'deleted_at' && empty($column['comment'])) {
                $column['comment'] = '删除时间';
            }
            $fields .= "    #[OA\\Property(property: '{$column['name']}', description: '{$column['comment']}', type: '{$column['swagger_type']}')]\n";
            $fields .= '    protected '.$column['base_type'].' $'.$column['name'].";\n\n";
        }

        foreach ($columns as $column) {
            $fields .= $this->getSet($column['name'], $column['base_type'], $column['comment'])."\n\n";
        }

        $fields = rtrim($fields, "\n");

        LaravelStub::from(__DIR__.'/stubs/entity/entity.stub')
            ->to(app_path('Entities'))
            ->name($className.'Entity')
            ->ext('php')
            ->replaces([
                'name' => $className,
                'fields' => $fields
            ])
            ->generate();
    }
}
