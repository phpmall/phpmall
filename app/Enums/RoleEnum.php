<?php

declare(strict_types=1);

namespace App\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

enum RoleEnum: string implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 管理员
     */
    case Manager = 'manager';

    /**
     * 销售方
     */
    case Seller = 'seller';

    /**
     * 供应方
     */
    case Supplier = 'supplier';

    /**
     * 购买方
     */
    case Buyer = 'buyer';
}
