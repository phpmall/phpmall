<?php

declare(strict_types=1);

namespace Juling\DevTools\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Juling\DevTools\Support\SchemaTrait;

class GenEnums extends Command
{
    use SchemaTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:enums';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate enum classes';

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
            $this->enumsTpl($className, $comment);
        }
    }

    public function enumsTpl(string $name, string $comment): void
    {
        $dist = app_path('Http/API/Enums/'.$name);
        if (! is_dir($dist)) {
            $this->ensureDirectoryExists($dist);
        }

        $content = file_get_contents(__DIR__.'/stubs/enums/status.stub');
        $content = str_replace([
            '{$name}',
            '{$comment}',
        ], [
            $name,
            $comment,
        ], $content);
        file_put_contents(app_path('Http/API/Enums/'.$name.'/'.$name.'StatusEnum.php'), $content);
    }
}
