<?php

declare(strict_types=1);

namespace App\Domains\Payment\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 退款状态枚举
 */
enum PaymentRefundChannelStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 待退款
     */
    case Status0 = 0;

    /**
     * 退款中
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
}
