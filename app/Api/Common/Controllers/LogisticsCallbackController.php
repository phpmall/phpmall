<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use App\Api\Common\Requests\LogisticsCallback\KuaidiNotifyRequest;
use App\Api\Common\Responses\LogisticsCallback\LogisticsNotifyResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class LogisticsCallbackController extends BaseController
{
    #[OA\Post(path: '/kuaidi/notify', summary: '快递回调通知', security: [[]], tags: ['公共工具'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: KuaidiNotifyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: LogisticsNotifyResponse::class))]
    public function kuaidiNotify(KuaidiNotifyRequest $request): JsonResponse
    {
        return $this->success();
    }
}
