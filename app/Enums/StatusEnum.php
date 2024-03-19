<?php

declare(strict_types=1);

namespace App\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Enums\EnumMethods;

/**
 * 状态枚举
 */
enum StatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 正常
     */
    case Normal = 1;

    /**
     * 禁用
     */
    case Disabled = 2;
}
