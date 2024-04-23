<?php

declare(strict_types=1);

namespace Juling\DevTools\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Juling\DevTools\Support\SchemaTrait;

class GenController extends Command
{
    use SchemaTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate controller classes';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $apiDir = app_path('Http/API');
        if (is_dir($apiDir)) {
            $this->deleteDirectories($apiDir);
        }

        $tables = $this->getTables();
        foreach ($tables as $table) {
            $className = Str::studly(Str::singular($table['name']));
            $comment = $table['comment'];
            if (Str::endsWith($comment, '表')) {
                $comment = Str::substr($comment, 0, -1);
            }
            $comment .= '模块';
            $columns = $this->getTableColumns($table['name']);

            $this->controllerTpl($className, $comment);
            $this->requestTpl($className, $columns);
            $this->responseTpl($className, $columns);
        }
    }

    private function controllerTpl(string $name, string $comment): void
    {
        $dist = app_path('Http/API/Controllers');
        if (! is_dir($dist)) {
            $this->ensureDirectoryExists($dist);
        }

        $content = file_get_contents(__DIR__.'/stubs/controller/controller.stub');
        $content = str_replace([
            '{$name}',
            '{$camelName}',
            '{$comment}',
        ], [
            $name,
            Str::camel($name),
            $comment,
        ], $content);
        file_put_contents(app_path('Http/API/Controllers/'.$name.'Controller.php'), $content);
    }

    private function requestTpl(string $name, array $columns): void
    {
        $dist = app_path('Http/API/Requests/'.$name);
        if (! is_dir($dist)) {
            $this->ensureDirectoryExists($dist);
        }

        $ignoreFields = ['id', 'created_at', 'updated_at', 'deleted_at'];

        $dataSets = ['required' => '', 'properties' => '', 'rule' => '', 'message' => ''];
        foreach ($columns as $column) {
            if (in_array($column['name'], $ignoreFields)) {
                continue;
            }
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
            $dataSets['required'] .= "        '".$column['name']."',\n";
            $dataSets['properties'] .= "        new OA\Property(property: '{$column['name']}', description: '{$column['comment']}', type: '{$column['swagger_type']}'),\n";
            $dataSets['rule'] .= "            '{$column['name']}' => 'require',\n";

            $column['comment'] = Str::replace([':', '：'], ':', $column['comment']);
            $endPosition = Str::position($column['comment'], ':');
            if ($endPosition !== false) {
                $column['comment'] = Str::substr($column['comment'], 0, $endPosition);
            }
            $dataSets['message'] .= "            '{$column['name']}.require' => '请设置{$column['comment']}',\n";
        }

        $dataSets = array_map(function ($item) {
            return rtrim($item, "\n");
        }, $dataSets);

        $this->writeRequest($name, 'CreateRequest', $dataSets['required'], $dataSets['properties'], $dataSets['rule'], $dataSets['message']);
        $this->writeRequest($name, 'QueryRequest', '', '', '', '');
        $this->writeRequest($name, 'UpdateRequest', $dataSets['required'], $dataSets['properties'], $dataSets['rule'], $dataSets['message']);
    }

    private function writeRequest($name, $suffix, $required, $properties, $rule, $message): void
    {
        if ($suffix === 'UpdateRequest') {
            $required = "        'id',\n".$required;
            $properties = "        new OA\Property(property: 'id', description: 'ID', type: 'integer'),\n".$properties;
            $rule = "            'id' => 'require',\n".$rule;
            $message = "            'id.require' => '请设置ID',\n".$message;
        }

        $content = file_get_contents(__DIR__.'/stubs/request/request.stub');
        $content = str_replace([
            '{$name}',
            '{$schema}',
            '{$dataSets[required]}',
            '{$dataSets[properties]}',
            '{$dataSets[rule]}',
            '{$dataSets[message]}',
        ], [
            $name,
            $name.$suffix,
            $required,
            $properties,
            $rule,
            $message,
        ], $content);
        file_put_contents(app_path('Http/API/Requests/'.$name.'/'.$name.$suffix.'.php'), $content);
    }

    private function responseTpl(string $name, array $columns): void
    {
        $dist = app_path('Http/API/Responses/'.$name);
        if (! is_dir($dist)) {
            $this->ensureDirectoryExists($dist);
        }

        $content = file_get_contents(__DIR__.'/stubs/response/query.stub');
        $content = str_replace([
            '{$name}',
        ], [
            $name,
        ], $content);
        file_put_contents(app_path('Http/API/Responses/'.$name.'/'.$name.'QueryResponse.php'), $content);

        $content = file_get_contents(__DIR__.'/stubs/response/destroy.stub');
        $content = str_replace([
            '{$name}',
        ], [
            $name,
        ], $content);
        file_put_contents(app_path('Http/API/Responses/'.$name.'/'.$name.'DestroyResponse.php'), $content);

        $ignoreFields = ['deleted_time', 'password', 'password_salt'];

        $fields = "\n";
        foreach ($columns as $column) {
            if (in_array($column['name'], $ignoreFields)) {
                continue;
            }

            if ($column['name'] === 'default') {
                $column['name'] = 'isDefault';
            }
            if ($column['name'] === 'id' && empty($column['comment'])) {
                $column['comment'] = 'ID';
            }
            $column['name'] = Str::camel($column['name']);
            $fields .= "    #[OA\Property(property: '{$column['name']}', description: '{$column['comment']}', type: '{$column['swagger_type']}')]\n";
            $fields .= '    private '.$column['base_type'].' $'.$column['name'].";\n\n";
        }

        foreach ($columns as $column) {
            if (in_array($column['name'], $ignoreFields)) {
                continue;
            }

            $column['name'] = Str::camel($column['name']);
            $fields .= $this->getSet($column['name'], $column['base_type'], $column['comment'])."\n\n";
        }

        $fields = rtrim($fields, "\n");

        $content = file_get_contents(__DIR__.'/stubs/response/response.stub');
        $content = str_replace([
            '{$name}',
            '{$fields}',
        ], [
            $name,
            $fields,
        ], $content);
        file_put_contents(app_path('Http/API/Responses/'.$name.'/'.$name.'Response.php'), $content);
    }
}
