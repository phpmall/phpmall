<?php

namespace Juling\Wechat\Traits;

use EasyWeChat\OpenPlatform\Application;
use Juling\Wechat\Events\OpenPlatform\Authorized;
use Juling\Wechat\Events\OpenPlatform\AuthorizeUpdated;
use Juling\Wechat\Events\OpenPlatform\Unauthorized;
use Juling\Wechat\Events\OpenPlatform\VerifyTicketRefreshed;

trait HandleOpenPlatformServerEvents
{
    /**
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \Throwable
     * @throws \ReflectionException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function handleServerEvents(Application $application, ?callable $callback = null): \Psr\Http\Message\ResponseInterface
    {
        $this->disableLaravelDebugbar();

        $server = $application->getServer();

        $server->handleAuthorized(function ($payload) {
            event(new Authorized($payload->toArray()));
        });

        $server->handleUnauthorized(function ($payload) {
            event(new Unauthorized($payload->toArray()));
        });

        $server->handleAuthorizeUpdated(function ($payload) {
            event(new AuthorizeUpdated($payload->toArray()));
        });

        $server->handleVerifyTicketRefreshed(function ($payload) {
            event(new VerifyTicketRefreshed($payload->toArray()));
        });

        if ($callback) {
            $callback($server);
        }

        return $server->serve();
    }

    protected function disableLaravelDebugbar(): void
    {
        $debugbar = 'Barryvdh\Debugbar\LaravelDebugbar';

        if (class_exists($debugbar)) {
            try {
                resolve($debugbar)->disable();
            } catch (\Throwable) {
                //
            }
        }
    }
}
