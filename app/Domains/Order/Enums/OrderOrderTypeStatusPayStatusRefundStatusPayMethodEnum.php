<?php

declare(strict_types=1);

namespace App\Domains\Order\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 支付方式枚举
 */
enum OrderOrderTypeStatusPayStatusRefundStatusPayMethodEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 微信
     */
    case PayMethod1 = 1;

    /**
     * 支付宝
     */
    case PayMethod2 = 2;

    /**
     * 余额
     */
    case PayMethod3 = 3;

    /**
     * 银联
     */
    case PayMethod4 = 4;
}
