<?php

declare(strict_types=1);

namespace App\Bundles\Wechat\Services;

use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\OfficialAccount\Application;
use Illuminate\Support\Facades\Cache;

class WechatService
{
    /**
     * 获取微信公众平台实例
     * @throws InvalidArgumentException
     */
    public function officialAccount(): Application
    {
        $config = config('wechat.official_account');

        $app = new Application($config);
        $app->setCache(Cache::store());

        return $app;
    }
}
