<?php

declare(strict_types=1);

namespace App\Managers\Wechat;

use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\OfficialAccount\Application;

class WechatService
{
    /**
     * 获取微信公众平台实例
     *
     * @throws InvalidArgumentException
     */
    public function officialAccount(): Application
    {
        $config = config('wechat.official_account');

        return new Application($config);
    }
}
