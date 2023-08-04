<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

class GenRoute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:route';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate route rules';

    protected array $ignoreList = ['Base', 'Auth'];

    /**
     * Execute the console command.
     *
     * @throws \ReflectionException
     */
    public function handle(): void
    {
        $files = array_merge(
            glob(app_path('Gateways/*/Controllers/*.php'))
        );

        $routes = [];
        foreach ($files as $file) {
            $file = str_replace('/', '\\', $file);
            preg_match('/app\\\\Gateways\\\\(\w+?)\\\\Controllers\\\\(\w+)Controller/', $file, $matches);
            if (! in_array($matches[2], $this->ignoreList)) {
                $class = ucfirst($matches[0]);

                $reflectionClass = new ReflectionClass($class);
                $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);
                $methods = array_filter($methods, function ($item) use ($class) {
                    return $item->class === $class;
                });

                foreach ($methods as $method) {
                    if ($matches[2] === 'Index' && $method->name === 'index') {
                        continue;
                    }
                    $methodDoc = $reflectionClass->getMethod($method->name)->getDocComment();
                    preg_match('/@httpMethod (\w+)/', $methodDoc, $m);
                    $httpMethod = $m[1] ?? 'get';
                    $routes[$matches[1]][] = [
                        'httpMethod' => $httpMethod,
                        'path' => Str::snake($matches[2]).($method->name === 'index' ? '' : '/'.$method->name),
                        'class' => $class,
                        'controller' => $matches[2],
                        'action' => $method->name,
                    ];
                }
            }
        }

        foreach ($routes as $m => $list) {
            $routeContent = '// Route';
            foreach ($list as $route) {
                $routeContent .= "\nRoute::{$route['httpMethod']}('{$route['path']}', [\\{$route['class']}::class, '{$route['action']}'])";
                if ($route['httpMethod'] === 'get') {
                    $name = Str::replace('/', '.', $route['path']);
                    $routeContent .= "->name('$name')";
                }
                $routeContent .= ';';
            }
            $routeContent .= "\n// end";

            $route_file = app_path('Gateways/'.$m.'/Routes/web.php');
            $content = file_get_contents($route_file);
            $content = preg_replace('/\/\/ Route.*?\/\/ end/is', $routeContent, $content);
            file_put_contents($route_file, $content);
        }
    }
}
