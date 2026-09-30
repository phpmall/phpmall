<?php

declare(strict_types=1);

namespace App\Domains\Product\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 评分星级枚举
 */
enum ProductReviewRatingEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 一星
     */
    case Rating1 = 1;

    /**
     * 二星
     */
    case Rating2 = 2;

    /**
     * 三星
     */
    case Rating3 = 3;

    /**
     * 四星
     */
    case Rating4 = 4;

    /**
     * 五星
     */
    case Rating5 = 5;
}
