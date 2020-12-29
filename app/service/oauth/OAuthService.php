<?php

declare(strict_types=1);

namespace app\service\oauth;

/**
 * Class OAuthService
 * @package app\service\oauth
 */
class OAuthService
{
    /**
     * @var string[]
     */
    private $supportOAuthType = [
        'wechat',
    ];

    /**
     * 在线授权
     * @param string $type 授权类型
     */
    public function authorize(string $type)
    {
        if (!in_array($type, $this->supportOAuthType)) {
            return json(['Unsupported OAuth type ' . $type]);
        }

    }
}
