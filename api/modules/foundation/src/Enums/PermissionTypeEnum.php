<?php

declare(strict_types=1);

namespace Juling\Foundation\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Enums\EnumMethods;

/**
 * 资源类型
 */
enum PermissionTypeEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 菜单
     */
    case Menu = 1;

    /**
     * 页面
     */
    case Page = 2;

    /**
     * 接口
     */
    case Api = 3;
}
