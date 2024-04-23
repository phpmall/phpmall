<?php

declare(strict_types=1);

namespace Juling\DevTools\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenTypescript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:typescript';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate typescript module';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $files = glob(storage_path('app/openapi/*.json'));
        foreach ($files as $file) {
            $module = basename($file, '.json');
            $data = json_decode(file_get_contents($file), true);

            $servicePath = storage_path('app/ts/services');
            if (! is_dir($servicePath)) {
                mkdir($servicePath, 0755, true);
            }
            $serviceContent = $this->genServices($data, $module);
            file_put_contents($servicePath.'/'.$module.'.ts', $serviceContent);

            $typePath = storage_path('app/ts/types');
            if (! is_dir($typePath)) {
                mkdir($typePath, 0755, true);
            }
            $typeContent = $this->genTypes($data, $module);
            file_put_contents($typePath.'/'.$module.'.d.ts', $typeContent);
        }
    }

    public function genServices(array $data, string $module): string
    {
        $content = "import request from '@/utils/request'\n";
        if (isset($data['paths'])) {
            $apis = []; // API接口
            $types = []; // 参数类型
            foreach ($data['paths'] as $path => $item) {
                $requestParams = '';
                $requestBody = '';

                foreach ($item as $method => $val) {
                    // query 参数
                    if (isset($val['parameters'])) {
                        $parameters = [];
                        foreach ($val['parameters'] as $v) {
                            $vType = 'string';
                            if (isset($v['example']) && is_int($v['example'])) {
                                $vType = 'number';
                            }
                            $parameters[$v['name']] = $vType;
                        }

                        $params = [];
                        foreach ($parameters as $k => $t) {
                            $params[] = $k.': '.$t;
                        }

                        $requestParams = implode(', ', $params);
                        $requestBody = ",\n        params: {".implode(', ', array_keys($parameters)).'}';
                    }

                    // formData 参数
                    if (isset($val['requestBody']['content']['application/json']['schema']['$ref'])) {
                        $request = $val['requestBody']['content']['application/json']['schema']['$ref'];
                        preg_match('/\/components\/schemas\/(\w+)/', $request, $m);
                        if (isset($m[1])) {
                            $interface = 'I'.$m[1];
                            $types[] = $interface;

                            if (empty($requestParams)) {
                                $requestParams .= 'formData: '.$interface;
                            } else {
                                $requestParams .= ', formData: '.$interface;
                            }

                            $requestBody .= ",\n        data: formData";
                        }
                    }

                    // 文件上传
                    if (isset($val['requestBody']['content']['multipart/form-data']['schema']['$ref'])) {
                        $request = $val['requestBody']['content']['multipart/form-data']['schema']['$ref'];
                        preg_match('/\/components\/schemas\/(\w+)/', $request, $m);
                        if (isset($m[1])) {
                            $interface = 'I'.$m[1];
                            $types[] = $interface;

                            if (empty($requestParams)) {
                                $requestParams .= 'formData: '.$interface;
                            } else {
                                $requestParams .= ', formData: '.$interface;
                            }

                            $requestBody .= ",\n        data: formData";
                            $requestBody .= ",\n        headers: { 'Content-Type': 'multipart/form-data' }";
                        }
                    }

                    $response = '<any>';
                    if (isset($val['responses'][200]['content']['application/json']['schema']['$ref'])) {
                        $response = $val['responses'][200]['content']['application/json']['schema']['$ref'];
                        preg_match('/\/components\/schemas\/(\w+)/', $response, $m);
                        if (isset($m[1])) {
                            $interface = 'I'.$m[1];
                            $types[] = $interface;
                            $response = '<'.$interface.'>';
                        }
                    }

                    $service = Str::camel(Str::replace('/', ' ', $path));
                    $url = (Str::substr($path, 0, 1) === '/') ? $module.$path : $module.'/'.$path;

                    $apis[] = "// [{$val['tags'][0]}] {$val['summary']}
export const {$service}Service = ({$requestParams}): Promise{$response} => {
    return request({
        url: '{$url}',
        method: '{$method}'{$requestBody}
    })
}\n";
                }
            }

            $content .= 'import type { '.implode(",\n", array_unique($types))." } from '@/types/{$module}.d'\n\n";

            $content .= implode("\n", $apis);
        }

        return $content;
    }

    private function genTypes(array $data, string $module): string
    {
        $content = '';
        if (isset($data['components']['schemas'])) {
            foreach ($data['components']['schemas'] as $type => $schema) {
                if (Str::contains($type, 'Schema')) {
                    continue;
                }
                if (! isset($schema['properties'])) {
                    exit($module.' 模块中的 '.$type.' 缺少 properties 参数');
                }
                $content .= $this->genTypeSchemas($type, $schema);
            }
        }

        return $content;
    }

    private function genTypeSchemas(string $interface, array $schema): string
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
                $type = 'I'.basename($property['$ref']);
            } else {
                exit($interface.' 对象 '.var_export($property, true).' 缺失类型');
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
