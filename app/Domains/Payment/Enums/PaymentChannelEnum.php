<?php

declare(strict_types=1);

namespace App\Domains\Payment\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 支付渠道枚举
 */
enum PaymentChannelEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 微信
     */
    case Channel1 = 1;

    /**
     * 支付宝
     */
    case Channel2 = 2;

    /**
     * 余额
     */
    case Channel3 = 3;

    /**
     * 银联
     */
    case Channel4 = 4;
}
