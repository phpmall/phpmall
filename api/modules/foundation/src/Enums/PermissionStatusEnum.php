<?php

declare(strict_types=1);

namespace Juling\Foundation\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Enums\EnumMethods;

/**
 * 资源状态
 */
enum PermissionStatusEnum: int implements EnumMethodInterface
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
