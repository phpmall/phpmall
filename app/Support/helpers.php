<?php

declare(strict_types=1);

use Illuminate\Support\Str;

if (! function_exists('mask_mobile')) {
    /**
     * 验证邮箱地址格式
     */
    function mask_mobile(string $mobile): string
    {
        return Str::mask($mobile, 3, 4);
    }
}

if (! function_exists('is_email')) {
    /**
     * 验证邮箱地址格式
     */
    function is_email(string $email): bool
    {
        return ! (filter_var($email, FILTER_VALIDATE_EMAIL) === false);
    }
}

if (! function_exists('is_mobile')) {
    /**
     * 验证手机号码格式
     */
    function is_mobile(string $mobile): bool
    {
        $rule = '/^1[3-9]\d{9}$/';

        return 1 === preg_match($rule, $mobile);
    }
}
