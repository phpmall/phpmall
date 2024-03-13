<?php

declare(strict_types=1);

namespace App\Foundation\DevTools\Support;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait SchemaTrait
{
    private array $ignoreTable = [
        'cache',
        'cache_locks',
        'failed_jobs',
        'jobs',
        'job_batches',
        'migrations',
        'password_reset_tokens',
        'personal_access_tokens',
        'sessions',
    ];

    private function getTables(): array
    {
        $tables = DB::select('show tables;');
        $database = env('DB_DATABASE');

        $tables = array_column($tables, 'Tables_in_'.$database);
        foreach ($tables as $key => $table) {
            if (in_array($table, $this->ignoreTable)) {
                unset($tables[$key]);
            }
        }

        return $tables;
    }

    private function getTableComment($tableName): string
    {
        $database = env('DB_DATABASE');
        $tableInfo = DB::select("SELECT `TABLE_COMMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$database' AND TABLE_NAME = '$tableName';");

        return $tableInfo[0]->TABLE_COMMENT;
    }

    private function getTableColumns($tableName): array
    {
        $database = env('DB_DATABASE');
        $sql = "SELECT COLUMN_NAME,COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '{$database}' AND TABLE_NAME = '{$tableName}'";
        $result = DB::select($sql);

        $comments = [];
        foreach ($result as $row) {
            $comments[$row->COLUMN_NAME] = $row->COLUMN_COMMENT;
        }

        $sql = 'desc '.$tableName;
        $result = DB::select($sql);

        $columns = [];
        foreach ($result as $row) {
            $row = collect($row)->toArray();
            $row['Comment'] = $comments[$row['Field']];
            $row['BaseType'] = $this->getFieldType($row['Type']);
            $row['SwaggerType'] = $row['BaseType'] === 'int' ? 'integer' : $row['BaseType'];
            $columns[] = $row;
        }

        return $columns;
    }

    private function getFieldType($type): string
    {
        preg_match('/(\w+)\(/', $type, $m);
        $type = $m[1] ?? $type;
        $type = str_replace(' unsigned', '', $type);
        if (in_array($type, ['bit', 'int', 'bigint', 'mediumint', 'smallint', 'tinyint', 'enum'])) {
            $type = 'int';
        }
        if (in_array($type, ['varchar', 'char', 'text', 'mediumtext', 'longtext'])) {
            $type = 'string';
        }
        if (in_array($type, ['decimal', 'float'])) {
            $type = 'float';
        }
        if (in_array($type, ['date', 'datetime', 'timestamp', 'time'])) {
            $type = 'string';
        }

        return $type;
    }

    private function getSet($field, $type, $comment): string
    {
        $capitalName = Str::studly($field);

        return <<<EOF
    /**
     * 获取{$comment}
     */
    public function get{$capitalName}(): $type
    {
        return \$this->$field;
    }

    /**
     * 设置{$comment}
     */
    public function set{$capitalName}($type \${$field}): void
    {
        \$this->$field = \${$field};
    }
EOF;
    }

    private function ensureDirectoryExists(array|string $dirs): void
    {
        $fs = new Filesystem();

        if (is_string($dirs)) {
            $dirs = [$dirs];
        }

        foreach ($dirs as $dir) {
            $fs->ensureDirectoryExists($dir);
        }
    }

    private function deleteDirectories(string $directory): void
    {
        $fs = new Filesystem();

        $fs->deleteDirectories($directory);
    }
}
