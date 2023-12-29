<?php

declare(strict_types=1);

use Illuminate\Support\Str;

if (! function_exists('mobile_mask')) {
    /**
     * 手机号脱敏
     */
    function mobile_mask(string $mobile): string
    {
        return Str::mask($mobile, '*', -4, 3);
    }
}
