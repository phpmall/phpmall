<?php

declare(strict_types=1);

namespace App\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

enum ErrorEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 用户不存在
     */
    case USER_NOT_EXIST = 10404;

    /**
     * 登录失败
     */
    case LOGIN_FAIL = 10501;
}
