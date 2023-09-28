<?php

declare(strict_types=1);

namespace App\Gateways\Common\Controllers;

use App\Bundles\Foundation\Constants\GlobalConst;
use App\Exceptions\CustomException;
use App\Gateways\Common\Requests\SmsSendRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;
use Throwable;

class SmsController extends BaseController
{
    #[OA\Post(path: '/common/sms', summary: '发送手机短信验证码', tags: ['短信'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SmsSendRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(SmsSendRequest $request): JsonResponse
    {
        $requestData = $request->validated();

        try {
            $code = mt_rand(100000, 999999);
            cache(GlobalConst::SMS_CACHE_PREFIX.$requestData['mobile'], $code, GlobalConst::SMS_CACHE_EXPIRE);

            $sms = new Sms();
            $sms->send($requestData['mobile'], 'SMS_CODE', ['code' => $code]);

            return $this->success('短信发送成功');
        } catch (CustomException $e) {
            return $this->error($e->getMessage());
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return $this->error('发送短信验证码错误');
        }
    }
}
