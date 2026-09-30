<?php

declare(strict_types=1);

namespace App\Domains\Product\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum ProductStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 下架
     */
    case Status0 = 0;

    /**
     * 上架
     */
    case Status1 = 1;
}
