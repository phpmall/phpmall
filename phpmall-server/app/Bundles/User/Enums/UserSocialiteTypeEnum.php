<?php

declare(strict_types=1);

namespace App\Bundles\User\Enums;

/**
 * 登录类型
 */
enum UserSocialiteTypeEnum: string
{
    /**
     * 用户名
     */
    case Username = 'username';

    /**
     * 手机号码
     */
    case Mobile = 'mobile';

    /**
     * 电子邮箱
     */
    case Email = 'email';

    /**
     * 微信
     */
    case Wechat = 'wechat';
}
