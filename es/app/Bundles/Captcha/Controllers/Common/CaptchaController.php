<?php

declare(strict_types=1);

namespace App\Bundles\Captcha\Controllers\Common;

use App\API\Common\Controllers\BaseController;
use App\Bundles\Captcha\Enums\CaptchaErrorEnum;
use App\Bundles\Captcha\Responses\CaptchaResponse;
use App\Bundles\Captcha\Services\CaptchaBundleService;
use Illuminate\Support\Str;
use Juling\Foundation\Exceptions\CustomException;
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
            $uuid = strval(Str::uuid());

            $captchaBundleService = new CaptchaBundleService();
            $captcha = $captchaBundleService->getCaptcha($uuid);

            $captchaResponse = new CaptchaResponse();
            $captchaResponse->setUuid($uuid);
            $captchaResponse->setCaptcha($captcha);

            return $this->success($captchaResponse->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(CaptchaErrorEnum::CAPTCHA_ERROR);
        }
    }
}
