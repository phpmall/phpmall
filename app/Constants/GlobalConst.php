<?php

declare(strict_types=1);

namespace App\Constants;

class GlobalConst
{
    /**
     * 用户JWT参数名
     */
    const JWT_USER_ID = 'user_id';

    /**
     * 短信模块缓存前缀
     */
    const SMS_CACHE_PREFIX = 'sms_';

    /**
     * 短信缓存有效时间
     */
    const SMS_CACHE_EXPIRE = 10 * 60;
}
