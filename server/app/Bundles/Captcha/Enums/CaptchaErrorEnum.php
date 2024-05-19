<?php

declare(strict_types=1);

namespace App\Bundles\Captcha\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Enums\EnumMethods;

enum CaptchaErrorEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 获取图片验证码错误
     */
    case CAPTCHA_ERROR = 10101;
}
