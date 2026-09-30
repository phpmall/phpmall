<?php

declare(strict_types=1);

namespace App\Domains\Order\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 订单来源枚举
 */
enum OrderOrderTypeStatusPayStatusRefundStatusPayMethodSourceEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * PC
     */
    case Source1 = 1;

    /**
     * H5
     */
    case Source2 = 2;

    /**
     * 小程序
     */
    case Source3 = 3;

    /**
     * App
     */
    case Source4 = 4;
}
