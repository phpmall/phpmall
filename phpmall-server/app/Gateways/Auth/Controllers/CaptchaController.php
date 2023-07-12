<?php

declare(strict_types=1);

namespace App\Gateways\Auth\Controllers;

use Exception;
use Focite\Captcha\Captcha;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

class CaptchaController extends BaseController
{
    #[OA\Get(path: '/captcha', summary: '图片验证码', tags: ['验证码'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): Response
    {
        try {
            return (new Captcha())->create([
                'length' => 4,
                'codeSet' => '2345678ABCDEFGHJKLMNPQRTUVWXY',
            ]);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
