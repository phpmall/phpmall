<?php

declare(strict_types=1);

namespace App\Domains\Order\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 售后状态枚举
 */
enum OrderRefundTypeStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 待商家处理
     */
    case Status0 = 0;

    /**
     * 商家同意
     */
    case Status1 = 1;

    /**
     * 商家拒绝
     */
    case Status2 = 2;

    /**
     * 退货中
     */
    case Status3 = 3;

    /**
     * 平台介入
     */
    case Status4 = 4;

    /**
     * 已退款
     */
    case Status5 = 5;

    /**
     * 已拒绝
     */
    case Status6 = 6;

    /**
     * 用户撤销
     */
    case Status7 = 7;
}
