<?php

declare(strict_types=1);

namespace App\Domains\Product\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 是否显示枚举
 */
enum ProductCategoryIsShowEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 否
     */
    case IsShow0 = 0;

    /**
     * 是
     */
    case IsShow1 = 1;
}
