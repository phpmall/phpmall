<?php

use Juling\Auth\JWT;

return [
    // 签名算法
    'algorithm' => JWT::ALGORITHM_HS256,
    // 钥匙
    'key' => env('APP_KEY', md5(__DIR__)),
    // 荷载
    'payload' => [
        // 签发者
        'iss' => env('APP_URL', ''),
        // 接收者
        'aud' => env('APP_URL', ''),
        // 签发时间
        'iat' => now()->timestamp,
        // 某个时间点后才能访问
        'nbf' => now()->timestamp,
        // 过期时间，默认一个月
        'exp' => now()->addMonths()->timestamp,
    ],
];
