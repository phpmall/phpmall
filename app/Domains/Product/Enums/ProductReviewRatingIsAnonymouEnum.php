<?php

declare(strict_types=1);

namespace App\Domains\Product\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 是否匿名枚举
 */
enum ProductReviewRatingIsAnonymouEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 否
     */
    case IsAnonymou0 = 0;

    /**
     * 是
     */
    case IsAnonymou1 = 1;
}
