<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use App\Api\Common\Responses\CaptchaResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Juling\Captcha\Services\CaptchaService;
use Juling\Foundation\Enums\BusinessEnum;
use OpenApi\Attributes as OA;
use Throwable;

class CaptchaController extends Controller
{
    private CaptchaService $captchaService;

    public function __construct(CaptchaService $captchaService)
    {
        $this->captchaService = $captchaService;
    }

    #[OA\Get(path: '/captcha', summary: '显示图片验证码', tags: ['验证码'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CaptchaResponse::class))]
    public function index(): JsonResponse
    {
        try {
            $captchaId = Str::uuid()->toString();
            $captchaBase64 = $this->captchaService->create($captchaId);

            $response = new CaptchaResponse;
            $response->setCaptchaId($captchaId);
            $response->setCaptchaImg($captchaBase64);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }
}
