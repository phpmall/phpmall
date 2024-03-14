<?php

declare(strict_types=1);

use Illuminate\Support\Str;

if (! function_exists('mobile_mask')) {
    /**
     * 显示脱敏手机号码
     */
    function mobile_mask(string $mobile): string
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

        return preg_match($rule, $mobile) === 1;
    }
}
