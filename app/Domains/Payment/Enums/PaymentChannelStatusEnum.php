<?php

declare(strict_types=1);

namespace App\Domains\Payment\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 支付状态枚举
 */
enum PaymentChannelStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 待支付
     */
    case Status0 = 0;

    /**
     * 支付中
     */
    case Status1 = 1;

    /**
     * 成功
     */
    case Status2 = 2;

    /**
     * 失败
     */
    case Status3 = 3;

    /**
     * 关闭
     */
    case Status4 = 4;
}
