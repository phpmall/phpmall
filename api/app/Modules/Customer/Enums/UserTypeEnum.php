<?php

declare(strict_types=1);

namespace App\Modules\Customer\Enums;

/**
 * 认证类型
 */
enum UserTypeEnum: string
{
    /**
     * 管理员
     */
    case Admin = 'admin';

    /**
     * 卖家
     */
    case Seller = 'seller';

    /**
     * 买家
     */
    case User = 'user';
}
