<?php

namespace App\Foundation\Wechat\Events\OpenPlatform;

use App\Foundation\Wechat\Events\OpenPlatform\OpenPlatformEvent;

/**
 * @method string getAppId()
 * @method string getCreateTime()
 * @method string getInfoType()
 * @method string getAuthorizerAppid()
 * @method string getAuthorizationCode()
 * @method string getAuthorizationCodeExpiredTime()
 * @method string getPreAuthCode()
 */
class AuthorizeUpdated extends OpenPlatformEvent
{
}
