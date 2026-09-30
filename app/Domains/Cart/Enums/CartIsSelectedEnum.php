<?php

declare(strict_types=1);

namespace App\Domains\Cart\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 是否选中枚举
 */
enum CartIsSelectedEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 未选中
     */
    case IsSelected0 = 0;

    /**
     * 已选中
     */
    case IsSelected1 = 1;
}
