<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PaymentCallbackController extends BaseController
{
    #[OA\Post(path: '/alipay/notify', summary: '支付宝支付回调', security: [[]], tags: ['公共工具'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function alipayNotify(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/wechat/notify', summary: '微信支付回调', security: [[]], tags: ['公共工具'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function wechatNotify(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/unionpay/notify', summary: '银联支付回调', security: [[]], tags: ['公共工具'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function unionpayNotify(): JsonResponse
    {
        return $this->success();
    }
}
