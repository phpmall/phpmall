<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use App\Api\Common\Responses\CaptchaResponse;
use Exception;
use Juling\Captcha\Captcha;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

class CaptchaController extends BaseController
{
    #[OA\Get(path: '/api/common/captcha', summary: '图片验证码', tags: ['验证码'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CaptchaResponse::class))]
    public function index(): JsonResponse
    {
        try {
            $uuid = strval(Str::uuid());

            $captcha = new Captcha();
            $base64 = $captcha->create($uuid);

            $captchaResponse = new CaptchaResponse();
            $captchaResponse->setCaptcha($base64);
            $captchaResponse->setUuid($uuid);

            return $this->success($captchaResponse->toArray());
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return $this->error('获取图片验证码错误');
        }
    }
}
