<?php

declare(strict_types=1);

namespace Juling\DevTools\Support;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait SchemaTrait
{
    private array $ignoreTables = [
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
        $tables = Schema::getTables();

        foreach ($tables as $key => $table) {
            if (in_array($table['name'], $this->ignoreTables)) {
                unset($tables[$key]);
            }
        }

        return $tables;
    }

    private function getTableColumns($tableName): array
    {
        $columns = Schema::getColumns($tableName);

        foreach ($columns as $key => $row) {
            $row['base_type'] = $this->getFieldType($row['type_name']);
            $row['swagger_type'] = $row['base_type'] === 'int' ? 'integer' : $row['base_type'];
            $columns[$key] = $row;
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
