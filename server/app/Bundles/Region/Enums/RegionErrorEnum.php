<?php

declare(strict_types=1);

namespace App\Bundles\Region\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Enums\EnumMethods;

enum RegionErrorEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 获取地区信息错误
     */
    case REGION_ERROR = 100001;
}
