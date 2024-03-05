<?php

declare(strict_types=1);

namespace App\Bundles\Captcha\Controllers\Portal;

use App\Bundles\Captcha\Responses\CaptchaResponse;
use App\Bundles\Captcha\Services\CaptchaBundleService;
use App\Exceptions\CustomException;
use App\Api\Portal\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;
use Throwable;

class CaptchaController extends BaseController
{
    #[OA\Get(path: '/captcha', summary: '图片验证码', tags: ['验证码'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CaptchaResponse::class))]
    public function index(): JsonResponse
    {
        try {
            $captchaService = new CaptchaBundleService();
            $result = $captchaService->getCaptcha();

            return $this->success($result);
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e->getMessage());

            return $this->error('获取图片验证码错误');
        }
    }
}
