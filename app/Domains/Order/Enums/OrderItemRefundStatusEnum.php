<?php

declare(strict_types=1);

namespace App\Domains\Order\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 售后状态枚举
 */
enum OrderItemRefundStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 无售后
     */
    case RefundStatus0 = 0;

    /**
     * 申请中
     */
    case RefundStatus1 = 1;

    /**
     * 已退款
     */
    case RefundStatus2 = 2;

    /**
     * 已拒绝
     */
    case RefundStatus3 = 3;
}
