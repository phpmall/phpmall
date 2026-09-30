<?php

declare(strict_types=1);

namespace App\Domains\Order\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 是否评价枚举
 */
enum OrderItemRefundStatusIsCommentedEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 未评价
     */
    case IsCommented0 = 0;

    /**
     * 已评价
     */
    case IsCommented1 = 1;
}
