<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenInterface extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:ts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate typescript interfaces';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $files = glob(public_path('swagger/*.json'));
        foreach ($files as $file) {
            $module = basename($file, '.json');

            $content = '';
            $data = json_decode(file_get_contents($file), true);
            if (isset($data['components']['schemas'])) {
                foreach ($data['components']['schemas'] as $type => $schema) {
                    if (Str::contains($type, 'Schema')) {
                        continue;
                    }
                    if (! isset($schema['properties'])) {
                        exit($module.' '.$type.' 缺少 properties 参数');
                    }
                    $content .= $this->genCode($type, $schema);
                }
            }

            $outDir = base_path('/../web/src/services/'.$module.'/models');
            if (! is_dir($outDir)) {
                mkdir($outDir, 0755, true);
            }

            file_put_contents($outDir.'/index.ts', $content);

            unlink($file);
        }
    }

    private function genCode(string $interface, array $schema): string
    {
        $c = "export interface I$interface {\n";

        foreach ($schema['properties'] as $name => $property) {
            if (isset($property['type'])) {
                $type = $property['type'];
                if (in_array($type, ['integer', 'float'])) {
                    $type = 'number';
                } elseif ($type === 'file') {
                    $type = 'string';
                } elseif ($type === 'array') {
                    if (isset($property['items']['$ref'])) {
                        $type = 'I'.basename($property['items']['$ref']).'[]';
                    } elseif (isset($property['items']['type'])) {
                        $type = $property['items']['type'];
                        if (in_array($type, ['integer', 'float'])) {
                            $type = 'number';
                        }
                        $type = $type.'[]';
                    }
                }
            } elseif (isset($property['$ref'])) {
                $type = 'I'.basename($property['$ref']).'[]';
            } else {
                dd($interface.' 对象 '.var_export($property, true).' 缺失类型');
            }

            $description = isset($property['description']) ? ' // '.$property['description'] : '';

            if (isset($schema['required']) && ! in_array($name, $schema['required'])) {
                $name = $name.'?';
            }

            $c .= "  $name: $type,$description\n";
        }

        return $c."}\n\n";
    }
}
