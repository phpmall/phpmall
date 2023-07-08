<?php

return [
    'wechat' => [
        'client_id' => 'client_id',
        'client_secret' => 'client_secret',
        'redirect' => 'socialite/callback?'.http_build_query(['provider' => 'wechat'], '', '&'),
        // 开放平台 - 第三方平台所需
        'component' => [
            // or 'app_id', 'component_app_id' as key
            'id' => 'component-app-id',
            // or 'app_token', 'access_token', 'component_access_token' as key
            'token' => 'component-access-token',
        ],
    ],
];
