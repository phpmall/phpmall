<?php

declare(strict_types=1);

namespace App\Bundles\Captcha\Services;

use App\Bundles\Captcha\Responses\CaptchaResponse;
use App\Exceptions\CustomException;
use Exception;
use Illuminate\Support\Str;
use Juling\Captcha\Captcha;

class CaptchaBundleService
{
    /**
     * @throws Exception
     */
    public function getCaptcha(): array
    {
        try {
            $uuid = strval(Str::uuid());

            $captcha = new Captcha();
            $base64 = $captcha->create($uuid);

            $captchaResponse = new CaptchaResponse();
            $captchaResponse->setCaptcha($base64);
            $captchaResponse->setUuid($uuid);

            return $captchaResponse->toArray();
        } catch (Exception $e) {
            throw new CustomException($e->getMessage());
        }
    }
}
