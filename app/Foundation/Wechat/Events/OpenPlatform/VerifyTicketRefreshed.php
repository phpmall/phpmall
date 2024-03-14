<?php

namespace App\Foundation\Wechat\Events\OpenPlatform;

use App\Foundation\Wechat\Events\OpenPlatform\OpenPlatformEvent;

/**
 * @method string getAppId()
 * @method string getCreateTime()
 * @method string getInfoType()
 * @method string getComponentVerifyTicket()
 */
class VerifyTicketRefreshed extends OpenPlatformEvent
{
}
