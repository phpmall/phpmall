<?php

declare(strict_types=1);

namespace App\Bundles\Manager\Enums;

/**
 * 管理员状态
 */
enum AdminUserStatusEnum: int
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
