<?php

declare(strict_types=1);

namespace App\Foundation\DevTools\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Foundation\DevTools\Support\SchemaTrait;

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
            $this->entityTpl($table);
        }
    }

    private function entityTpl(string $tableName): void
    {
        $className = Str::studly(Str::singular($tableName));
        $columns = $this->getTableColumns($tableName);

        $fields = "\n";
        foreach ($columns as $column) {
            if ($column['Field'] === 'default') {
                $column['Field'] = 'isDefault';
            }
            if ($column['Field'] === 'id' && empty($column['Comment'])) {
                $column['Comment'] = 'ID';
            }
            $fields .= "    #[OA\\Property(property: '{$column['Field']}', description: '{$column['Comment']}', type: '{$column['SwaggerType']}')]\n";
            $fields .= '    protected '.$column['BaseType'].' $'.$column['Field'].";\n\n";
        }

        foreach ($columns as $column) {
            $fields .= $this->getSet($column['Field'], $column['BaseType'], $column['Comment'])."\n\n";
        }

        $fields = rtrim($fields, "\n");

        $content = file_get_contents(__DIR__.'/stubs/entity/entity.stub');
        $content = str_replace([
            '{$name}',
            '{$fields}',
        ], [
            $className,
            $fields,
        ], $content);

        file_put_contents(app_path('Entities/'.$className.'Entity.php'), $content);
    }
}
