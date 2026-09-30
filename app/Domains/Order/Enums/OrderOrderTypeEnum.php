<?php

declare(strict_types=1);

namespace App\Domains\Order\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 订单类型枚举
 */
enum OrderOrderTypeEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 普通
     */
    case OrderType1 = 1;

    /**
     * 秒杀
     */
    case OrderType2 = 2;

    /**
     * 拼团
     */
    case OrderType3 = 3;

    /**
     * 分销
     */
    case OrderType4 = 4;
}
