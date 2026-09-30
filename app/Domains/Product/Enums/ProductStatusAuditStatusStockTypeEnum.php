<?php

declare(strict_types=1);

namespace App\Domains\Product\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 库存类型枚举
 */
enum ProductStatusAuditStatusStockTypeEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 统一库存
     */
    case StockType1 = 1;

    /**
     * 规格独立库存
     */
    case StockType2 = 2;
}
