<?php

declare(strict_types=1);

namespace App\Bundles\Wechat\Services;

class WechatService
{
    /**
     * 获取微信公众平台实例
     */
    public function officialAccount(): Application
    {
        $config = config('wechat.official_account');

        $app = new Application($config);
        $app->setCache();
        
        return $app;
    }
}
