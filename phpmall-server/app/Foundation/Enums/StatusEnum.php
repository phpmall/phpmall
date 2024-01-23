<?php

declare(strict_types=1);

namespace App\Foundation\Enums;

/**
 * 状态
 */
enum StatusEnum: int
{
    /**
     * 正常
     */
    case Normal = 1;

    /**
     * 禁用
     */
    case Disabled = 2;

    /**
     * 锁定
     */
    case Locked = 3;

}
