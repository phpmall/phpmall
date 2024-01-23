<?php

declare(strict_types=1);

namespace App\Bundles\Push\Commands;

use App\Bundles\Push\PushServer;
use ReflectionClass;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Workerman\Worker;

class PushServeCommand extends Command
{
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setName('ws:serve')
            ->addArgument('action')
            ->addOption('mode', '-d')
            ->setDescription('The websocket serve.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        global $argv;

        $argv[0] = $input->getArgument('action');
        $argv[1] = $input->getOption('mode');

        $config = config('push');
        $config['server']['handler'] = PushServer::class;
        $config['server']['constructor'] = [
            $config['api'], // 服务端API地址
            [
                $config['app_key'] => [
                    'channel_hook' => $config['channel_hook'],
                    'app_secret' => $config['app_secret'],
                ],
            ],
        ];

        $this->workerStart('Websocket', $config['server']);

        Worker::runAll();

        return 0;
    }

    /**
     * Start worker
     */
    private function workerStart($processName, $config): void
    {
        $propertyMap = [
            'count',
            'user',
            'group',
            'reloadable',
            'reusePort',
            'transport',
            'protocol',
        ];

        $worker = new Worker($config['listen'], $config['context'] ?? []);
        $worker->name = $processName;

        foreach ($propertyMap as $property) {
            if (isset($config[$property])) {
                $worker->$property = $config[$property];
            }
        }

        $worker->onWorkerStart = function ($worker) use ($config) {
            if (isset($config['handler'])) {
                if (! class_exists($config['handler'])) {
                    echo "process error: class {$config['handler']} not exists\r\n";

                    return;
                }

                $reflection = new ReflectionClass($config['handler']);
                $instance = $reflection->newInstanceArgs($config['constructor'] ?? []);
                $this->workerBind($worker, $instance);
            }
        };
    }

    /**
     * Bind worker
     */
    private function workerBind($worker, $class): void
    {
        $callbackMap = [
            'onConnect',
            'onMessage',
            'onClose',
            'onError',
            'onBufferFull',
            'onBufferDrain',
            'onWorkerStop',
            'onWebSocketConnect',
            'onWorkerReload',
        ];

        foreach ($callbackMap as $name) {
            if (method_exists($class, $name)) {
                $worker->$name = [$class, $name];
            }
        }

        if (method_exists($class, 'onWorkerStart')) {
            call_user_func([$class, 'onWorkerStart'], $worker);
        }
    }
}
