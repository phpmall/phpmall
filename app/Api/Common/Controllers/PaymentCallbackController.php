<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use App\Api\Common\Requests\PaymentCallback\AlipayNotifyRequest;
use App\Api\Common\Requests\PaymentCallback\UnionpayNotifyRequest;
use App\Api\Common\Requests\PaymentCallback\WechatNotifyRequest;
use App\Api\Common\Responses\PaymentCallback\PaymentNotifyResponse;
use App\Jobs\OrderPaid;
use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Services\PaymentGatewayInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PaymentCallbackController extends BaseController
{
    public function __construct(
        private readonly PaymentGatewayInterface $gateway,
    ) {}

    #[OA\Post(path: '/alipay/notify', summary: '支付宝支付回调', security: [[]], tags: ['公共工具'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AlipayNotifyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PaymentNotifyResponse::class))]
    public function alipayNotify(AlipayNotifyRequest $request): JsonResponse
    {
        return $this->handleNotify($request->validated());
    }

    #[OA\Post(path: '/wechat/notify', summary: '微信支付回调', security: [[]], tags: ['公共工具'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatNotifyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PaymentNotifyResponse::class))]
    public function wechatNotify(WechatNotifyRequest $request): JsonResponse
    {
        return $this->handleNotify($request->validated());
    }

    #[OA\Post(path: '/unionpay/notify', summary: '银联支付回调', security: [[]], tags: ['公共工具'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UnionpayNotifyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PaymentNotifyResponse::class))]
    public function unionpayNotify(UnionpayNotifyRequest $request): JsonResponse
    {
        return $this->handleNotify($request->validated());
    }

    private function handleNotify(array $data): JsonResponse
    {
        $result = $this->gateway->notify($data);

        $response = new PaymentNotifyResponse;

        if (! ($result['success'] ?? false)) {
            $response->setSuccess(false);
            $response->setMessage($result['message'] ?? 'notify failed');

            return $this->success($response->toArray());
        }

        $paymentNo = $result['payment_no'] ?? '';
        $payment = Payment::where('payment_no', $paymentNo)->first();

        if ($payment && $payment->status === 0) {
            $payment->status = 1;
            $payment->paid_at = $result['paid_at'] ?? now();
            $payment->transaction_id = $result['third_party_no'] ?? null;
            $payment->save();

            event(new OrderPaid($payment->order_id, $payment->toArray()));
        }

        $response->setSuccess(true);
        $response->setMessage('notify processed');

        return $this->success($response->toArray());
    }
}
