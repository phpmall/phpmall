<?php

declare(strict_types=1);

namespace app\manager\lbs;

use think\Service as ServiceProvider;

/**
 * Class LbsServiceProvider
 * @package app\manager\lbs
 */
class LbsServiceProvider extends ServiceProvider
{
    /**
     * 服务注册
     */
    public function register()
    {
        $this->app->bind('lbs', LbsManager::class);
    }

    /**
     * 服务启动
     */
    public function boot()
    {
        //
    }
}
