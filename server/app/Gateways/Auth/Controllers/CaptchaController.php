<?php

declare(strict_types=1);

namespace App\Gateways\Auth\Controllers;

use Exception;
use Focite\Captcha\Captcha;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

class CaptchaController extends BaseController
{
    #[OA\Get(path: '/auth/captcha', summary: '图片验证码', tags: ['验证码'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): Response
    {
        try {
            $uuid = Str::uuid();

            return $this->success([
                'captcha' => (new Captcha())->create(strval($uuid)),
                'uuid' => $uuid,
            ]);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
