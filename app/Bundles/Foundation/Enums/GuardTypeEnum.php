<?php

declare(strict_types=1);

namespace App\Bundles\Foundation\Enums;

/**
 * 认证类型
 */
enum GuardTypeEnum: string
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
     * 供应商
     */
    case Supplier = 'supplier';

    /**
     * 买家
     */
    case User = 'user';
}
