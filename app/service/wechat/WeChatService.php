<?php

declare(strict_types=1);

namespace app\service\wechat;

use EasyWeChat\Factory;
use EasyWeChat\OfficialAccount\Application;

/**
 * Class WeChatService
 * @package app\service\wechat
 */
class WeChatService
{
    /**
     * @var Application
     */
    private $mpApp;

    public function __construct()
    {
        $config = [
            'app_id' => 'wx3cf0f39249eb0exx',
            'secret' => 'f1c242f4f28f735d4687abb469072axx',

            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',

            //...
        ];

        $this->mpApp = Factory::officialAccount($config);
    }
}
