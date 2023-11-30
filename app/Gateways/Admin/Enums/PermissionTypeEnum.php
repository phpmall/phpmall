<?php

declare(strict_types=1);

namespace App\Gateways\Admin\Enums;

/**
 * 资源类型
 */
enum PermissionTypeEnum: int
{
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
