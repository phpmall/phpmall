<?php

declare(strict_types=1);

namespace App\Bundles\User\Enums;

/**
 * 用户状态
 */
enum UserStatusEnum: int
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
