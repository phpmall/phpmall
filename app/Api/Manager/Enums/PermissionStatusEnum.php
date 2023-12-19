<?php

declare(strict_types=1);

namespace App\Api\Manager\Enums;

/**
 * 资源状态
 */
enum PermissionStatusEnum: int
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
