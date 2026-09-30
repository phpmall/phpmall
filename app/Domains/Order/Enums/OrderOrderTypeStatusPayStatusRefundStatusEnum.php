<?php

declare(strict_types=1);

namespace App\Domains\Order\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 退款状态枚举
 */
enum OrderOrderTypeStatusPayStatusRefundStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 无退款
     */
    case RefundStatus0 = 0;

    /**
     * 退款申请中
     */
    case RefundStatus10 = 10;

    /**
     * 退款中
     */
    case RefundStatus20 = 20;

    /**
     * 已退款
     */
    case RefundStatus30 = 30;

    /**
     * 拒绝退款
     */
    case RefundStatus40 = 40;
}
