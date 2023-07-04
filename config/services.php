<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'easy-sms' => [
        // HTTP 请求的超时时间（秒）
        'timeout' => 5.0,

        // 默认发送配置
        'default' => [
            // 网关调用策略，默认：顺序调用
            'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

            // 默认可用的发送网关
            'gateways' => ['aliyun'],
        ],
        // 可用的网关配置
        'gateways' => [
            'errorlog' => [
                'file' => storage_path('logs/sms.log'),
            ],
            'aliyun' => [
                'access_key_id' => env('SMS_ALIYUN_KEY_ID'),
                'access_key_secret' => env('SMS_ALIYUN_API_KEY'),
                'sign_name' => env('SMS_ALIYUN_SIGN_NAME'),
            ],
            'huyi' => [
                'api_id' => env('SMS_HUYI_API_ID'),
                'api_key' => env('SMS_HUYI_API_KEY'),
                'signature' => env('SMS_HUYI_API_SIGN'),
            ],
        ],
        // 短信模板
        'templates' => [
            'SMS_CODE' => ['SMS_100000000' => '您的验证码为: ${code}，请勿泄露于他人!'],
            // ...
        ],
    ],

];
