<?php

declare(strict_types=1);

namespace App\Modules\Wechat\Services;

use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\OfficialAccount\Application;
use Illuminate\Support\Facades\Cache;

class OfficialAccountService extends BaseService
{
    private ?Application $app;

    /**
     * 设置公众号配置
     *
     * @throws InvalidArgumentException
     */
    public function __construct()
    {
        $config = config('wechat.official_account');

        $this->app = new Application($config);
        $this->app->setCache(Cache::store());
    }

    public function getApp(): Application
    {
        return $this->app;
    }
}
