<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use App\Api\Common\Requests\Sms\SmsCodeRequest;
use App\Api\Common\Responses\SmsCodeResponse;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Juling\Captcha\Services\CaptchaService;
use Juling\Foundation\Enums\BusinessEnum;
use OpenApi\Attributes as OA;
use Throwable;

class SmsController extends Controller
{
    private CaptchaService $captchaService;

    public function __construct(CaptchaService $captchaService)
    {
        $this->captchaService = $captchaService;
    }

    #[OA\Post(path: '/sms/code', summary: '发送短信验证码', tags: ['手机短信'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SmsCodeResponse::class))]
    public function code(SmsCodeRequest $request): JsonResponse
    {
        try {
            $captchaId = $request->get(SmsCodeRequest::getCaptchaId, '');
            $captchaCode = $request->get(SmsCodeRequest::getCaptchaCode, '');
            if (! $this->captchaService->check($captchaId, $captchaCode)) {
                throw new CustomException('图片验证码错误');
            }

            $codeId = Str::random(3);
            $smsCode = mt_rand(1000, 9999);
            // TODO send sms code to user phone

            $smsCodeResp = new SmsCodeResponse;
            $smsCodeResp->setCodeId($codeId);
            $smsCodeResp->setStatus(1);

            return $this->success($smsCodeResp->toArray());
        } catch (Throwable $e) {
            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }
}
