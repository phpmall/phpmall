<?php

declare(strict_types=1);

namespace Juling\Foundation\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Enums\EnumMethods;

/**
 * 角色状态
 */
enum RoleStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 正常
     */
    case Normal = 1;

    /**
     * 禁用
     */
    case Disable = 2;
}
