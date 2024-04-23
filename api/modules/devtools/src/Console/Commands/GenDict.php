<?php

declare(strict_types=1);

namespace Juling\DevTools\Console\Commands;

use Illuminate\Console\Command;
use Juling\DevTools\Support\SchemaTrait;

class GenDict extends Command
{
    use SchemaTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:dict';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate database dict';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $content = "# 数据字典\n\n";

        $tables = $this->getTables();
        foreach ($tables as $table) {
            $content .= "### {$table['comment']}(`{$table['name']}`)\n";

            $columns = $this->getTableColumns($table['name']);
            $content .= $this->getContent($columns);
        }

        file_put_contents(storage_path('app/dict.md'), $content);
    }

    public function getContent($columns): string
    {
        $content = "| 列名 | 数据类型 | 索引 | 是否为空 | 描述 |\n";
        $content .= "| ------- | --------- | --------- | --------- | -------------- |\n";
        foreach ($columns as $column) {
            $isNull = $column['nullable'] ? '是' : '否';
            $content .= "| {$column['name']} | {$column['type']} | {$column['Key']} | $isNull | {$column['comment']} |\n";
        }
        $content .= "\n";

        return $content;
    }
}
