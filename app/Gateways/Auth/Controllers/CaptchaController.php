<?php

declare(strict_types=1);

namespace App\Gateways\Auth\Controllers;

use App\Gateways\Auth\Responses\CaptchaResponse;
use Exception;
use Focite\Captcha\Captcha;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

class CaptchaController extends BaseController
{
    #[OA\Get(path: '/captcha', summary: '图片验证码', tags: ['验证码'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CaptchaResponse::class))]
    public function index(): JsonResponse
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
