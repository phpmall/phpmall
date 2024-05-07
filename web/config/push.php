<?php

declare(strict_types=1);

use Juling\Pusher\PushServer;

return [
    'server' => [
        'handler' => PushServer::class,
        'listen' => 'websocket://0.0.0.0:2347',
        'count' => 1, // 必须是1
        'reloadable' => false, // 执行reload不重启
        'constructor' => [
            'api_listen' => 'http://0.0.0.0:2348', // 服务端推送地址
            'app_info' => [
                env('PUSH_APP_KEY') => [
                    'app_secret' => env('PUSH_APP_SECRET', ''),
                    'channel_hook' => env('APP_URL').'/push/hook',
                ],
            ],
        ],
    ],
];
