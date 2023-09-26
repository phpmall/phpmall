<?php

declare(strict_types=1);

namespace App\Gateways\Auth\Responses;

use Focite\Generator\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CaptchaResponse')]
class CaptchaResponse
{
    use ArrayObject;

    #[OA\Property(property: 'captcha', description: '图片验证码', type: 'string', example: '123456'), ]
    private string $captcha;

    #[OA\Property(property: 'uuid', description: '验证码UUID', type: 'string', example: '123456'), ]
    private string $uuid;

    public function setCaptcha(string $captcha): void
    {
        $this->captcha = $captcha;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }
}
