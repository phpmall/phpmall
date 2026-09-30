<?php

declare(strict_types=1);

namespace App\Domains\Product\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 是否新品枚举
 */
enum ProductStatusAuditStatusStockTypeIsHotIsNewEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 否
     */
    case IsNew0 = 0;

    /**
     * 是
     */
    case IsNew1 = 1;
}
