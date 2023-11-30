<?php

declare(strict_types=1);

namespace App\Gateways\Common\Controllers;

use App\Foundation\Controllers\Controller;
use App\Gateways\Common\Responses\CaptchaResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Juling\Captcha\Captcha;
use OpenApi\Attributes as OA;

class CaptchaController extends Controller
{
    #[OA\Get(path: '/common/captcha', summary: '图片验证码', tags: ['验证码'])]
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
