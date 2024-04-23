<?php

declare(strict_types=1);

namespace Juling\Foundation\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Enums\EnumMethods;

/**
 * 认证类型
 */
enum GuardEnum: string implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 管理员
     */
    case Admin = 'admin';
}
