<?php

declare(strict_types=1);

namespace App\Domains\Product\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 审核状态枚举
 */
enum ProductStatusAuditStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 待审核
     */
    case AuditStatus0 = 0;

    /**
     * 通过
     */
    case AuditStatus1 = 1;

    /**
     * 拒绝
     */
    case AuditStatus2 = 2;
}
