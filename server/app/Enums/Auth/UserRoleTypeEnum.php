<?php

declare(strict_types=1);

namespace App\Enums\Auth;

enum UserRoleTypeEnum: string
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
}
