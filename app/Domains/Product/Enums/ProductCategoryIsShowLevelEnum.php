<?php

declare(strict_types=1);

namespace App\Domains\Product\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 层级枚举
 */
enum ProductCategoryIsShowLevelEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 一级
     */
    case Level1 = 1;

    /**
     * 二级
     */
    case Level2 = 2;

    /**
     * 三级
     */
    case Level3 = 3;
}
