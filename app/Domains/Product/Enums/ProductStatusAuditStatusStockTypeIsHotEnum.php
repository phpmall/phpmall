<?php

declare(strict_types=1);

namespace App\Domains\Product\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 是否热销枚举
 */
enum ProductStatusAuditStatusStockTypeIsHotEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 否
     */
    case IsHot0 = 0;

    /**
     * 是
     */
    case IsHot1 = 1;
}
