<?php

declare(strict_types=1);

namespace App\Modules\Wechat\Services\Message;

use Closure;

class MessageHandler
{
    public function __invoke($message, Closure $next)
    {
        if ($message->MsgType === 'text') {
            //...
        }

        return $next($message);
    }
}
