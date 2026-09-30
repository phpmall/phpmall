<?php

declare(strict_types=1);

namespace App\Domains\Order\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 退款类型枚举
 */
enum OrderRefundTypeEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 仅退款
     */
    case Type1 = 1;

    /**
     * 退货退款
     */
    case Type2 = 2;

    /**
     * 换货
     */
    case Type3 = 3;
}
