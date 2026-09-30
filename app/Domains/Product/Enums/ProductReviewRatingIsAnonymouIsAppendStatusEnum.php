<?php

declare(strict_types=1);

namespace App\Domains\Product\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum ProductReviewRatingIsAnonymouIsAppendStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 隐藏
     */
    case Status0 = 0;

    /**
     * 显示
     */
    case Status1 = 1;
}
