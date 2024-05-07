<?php

declare(strict_types=1);

namespace App\Bundles\Captcha\Services;

use Exception;
use Juling\Captcha\Captcha;

class CaptchaBundleService
{
    /**
     * @throws Exception
     */
    public function getCaptcha(string $uuid): string
    {
        $captcha = new Captcha();

        return $captcha->create($uuid);
    }
}
