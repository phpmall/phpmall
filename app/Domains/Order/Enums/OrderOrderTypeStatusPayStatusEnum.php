<?php

declare(strict_types=1);

namespace App\Domains\Order\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 支付状态枚举
 */
enum OrderOrderTypeStatusPayStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 未支付
     */
    case PayStatus0 = 0;

    /**
     * 已支付
     */
    case PayStatus20 = 20;

    /**
     * 部分退款
     */
    case PayStatus30 = 30;

    /**
     * 全额退款
     */
    case PayStatus100 = 100;
}
