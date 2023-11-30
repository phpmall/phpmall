<?php

declare(strict_types=1);

namespace App\Gateways\Admin\Enums;

/**
 * 角色状态
 */
enum RoleStatusEnum: int
{
    /**
     * 正常
     */
    case Normal = 1;

    /**
     * 禁用
     */
    case Disable = 2;
}
