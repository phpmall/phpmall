<?php

declare (strict_types=1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Db;
use think\helper\Str;

/**
 * Class ModelGenerator
 * @package app\command
 */
class ModelGenerator extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('make:model:all')
            ->setDescription('Generator all model classes');
    }

    protected function execute(Input $input, Output $output)
    {
        $database = env('DB_DATABASE');
        $prefix = env('DB_PREFIX');

        $tables = Db::query('show tables');
        foreach ($tables as $key => $table) {
            $table = $table['Tables_in_' . $database];
            $name = str_replace($prefix, '', $table);
            $class = Str::studly($name);
            $model = $this->generator($class, $name);
            file_put_contents(app_path() . 'entity/' . $class . '.php', $model);
        }

        $output->writeln('Model generation completed');
    }

    /**
     * @param $class
     * @param $table
     * @return string
     */
    private function generator($class, $table): string
    {
        $tpl = <<<EOF
<?php

declare(strict_types=1);

namespace app\\entity;

use think\\Model;

/**
 * Class {$class}
 * @package app\\entity
 */
class {$class} extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected \$table = '{$table}';
EOF;

        return $tpl . "\n}";
    }
}
