<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenTsInterface extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:ts-interface';

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
        $files = glob(storage_path('framework/cache/*.json'));
        foreach ($files as $file) {
            $module = basename($file, '.json');

            $content = '';
            $data = json_decode(file_get_contents($file), true);
            if (isset($data['components']['schemas'])) {
                foreach ($data['components']['schemas'] as $type => $schema) {
                    if (! isset($schema['properties'])) {
                        dump($module . ' ' . $type);
                        dd($schema);
                    }
                    $content .= $this->genCode($type, $schema);
                }
            }

            $outDir = dirname(base_path()) . '/phpmall-'.$module. '/src/types/index.ts';
            file_put_contents($outDir, $content);

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
                        $type = $property['items']['type'].'[]';
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
