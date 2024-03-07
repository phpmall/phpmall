<?php

declare(strict_types=1);

namespace App\Bundles\Sms\Controllers\Portal;

use App\Api\Portal\Controllers\BaseController;
use App\Bundles\Sms\Requests\SmsSendRequest;
use App\Bundles\Sms\Services\SmsBundleService;
use App\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;
use Throwable;

class SmsController extends BaseController
{
    #[OA\Post(path: '/sms', summary: '发送手机短信验证码', tags: ['短信'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SmsSendRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(SmsSendRequest $request): JsonResponse
    {
        try {
            $requestData = $request->validated();

            $sms = new SmsBundleService();
            $sms->sendCode($requestData['mobile']);

            return $this->success('短信发送成功');
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e->getMessage());

            return $this->error('发送短信验证码错误');
        }
    }
}
