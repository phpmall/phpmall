<?php

declare(strict_types=1);

namespace App\Domains\Product\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 是否追评枚举
 */
enum ProductReviewRatingIsAnonymouIsAppendEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 否
     */
    case IsAppend0 = 0;

    /**
     * 是
     */
    case IsAppend1 = 1;
}
