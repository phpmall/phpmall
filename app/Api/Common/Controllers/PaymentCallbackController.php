<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use App\Api\Common\Requests\PaymentCallback\AlipayNotifyRequest;
use App\Api\Common\Requests\PaymentCallback\UnionpayNotifyRequest;
use App\Api\Common\Requests\PaymentCallback\WechatNotifyRequest;
use App\Api\Common\Responses\PaymentCallback\PaymentNotifyResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PaymentCallbackController extends BaseController
{
    #[OA\Post(path: '/alipay/notify', summary: '支付宝支付回调', security: [[]], tags: ['公共工具'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AlipayNotifyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PaymentNotifyResponse::class))]
    public function alipayNotify(AlipayNotifyRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/wechat/notify', summary: '微信支付回调', security: [[]], tags: ['公共工具'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatNotifyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PaymentNotifyResponse::class))]
    public function wechatNotify(WechatNotifyRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/unionpay/notify', summary: '银联支付回调', security: [[]], tags: ['公共工具'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UnionpayNotifyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PaymentNotifyResponse::class))]
    public function unionpayNotify(UnionpayNotifyRequest $request): JsonResponse
    {
        return $this->success();
    }
}
