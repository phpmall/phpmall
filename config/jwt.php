<?php

declare(strict_types=1);

use Juling\Auth\JWT;

return [
    // 签名算法
    'algorithm' => env('JWT_ALGORITHM', JWT::ALGORITHM_RS256),

    // 私钥路径（用于签名）
    'privateKey' => storage_path('jwt/jwt.key'),

    // 公钥路径（用于验证）
    'publicKey' => storage_path('jwt/jwt.key.pub'),

    // 对称加密密钥（HS256/HS384/HS512 时使用）
    'key' => env('APP_KEY', md5(__DIR__)),

    // 默认荷载
    'payload' => [
        // 签发者
        'iss' => env('APP_URL', ''),
        // 接收者
        'aud' => env('APP_URL', ''),
        // 签发时间
        'iat' => now()->timestamp,
        // 生效时间
        'nbf' => now()->timestamp,
    ],

    // Token 过期时间（分钟）
    'ttl' => (int) env('JWT_TTL', 120), // 2 小时

    // Refresh Token 过期时间（分钟）
    'refresh_ttl' => (int) env('JWT_REFRESH_TTL', 10080), // 7 天
];
