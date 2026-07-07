<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use App\Api\Common\Requests\PaymentCallback\AlipayNotifyRequest;
use App\Api\Common\Requests\PaymentCallback\UnionpayNotifyRequest;
use App\Api\Common\Requests\PaymentCallback\WechatNotifyRequest;
use App\Api\Common\Responses\PaymentCallback\PaymentNotifyResponse;
use App\Events\OrderPaid;
use App\Modules\Order\Models\Order;
use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Services\PaymentGatewayInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class PaymentCallbackController extends BaseController
{
    private const int PAYMENT_STATUS_PAID = 1;

    private const int ORDER_STATUS_PAID = 20;

    private const int ORDER_PAY_STATUS_PAID = 20;

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

        if ($payment === null) {
            $response->setSuccess(true);
            $response->setMessage('notify processed');

            return $this->success($response->toArray());
        }

        if ((int) $payment->status === self::PAYMENT_STATUS_PAID) {
            $response->setSuccess(true);
            $response->setMessage('payment already processed');

            return $this->success($response->toArray());
        }

        DB::transaction(function () use ($payment, $result, $data): void {
            $paidAt = $result['paid_at'] ?? now();
            $payment->status = self::PAYMENT_STATUS_PAID;
            $payment->paid_at = $paidAt;
            $payment->transaction_id = $result['third_party_no'] ?? null;
            $payment->notify_raw = $data;
            $payment->save();

            $order = Order::where('id', $payment->order_id)->first();

            if ($order !== null && (int) $order->status !== self::ORDER_STATUS_PAID) {
                $order->status = self::ORDER_STATUS_PAID;
                $order->pay_status = self::ORDER_PAY_STATUS_PAID;
                $order->pay_time = $paidAt;
                $order->pay_transaction_id = $result['third_party_no'] ?? null;
                $order->pay_method = $payment->channel;
                $order->save();
            }
        });

        event(new OrderPaid($payment->order_id, $payment->toArray()));

        $response->setSuccess(true);
        $response->setMessage('notify processed');

        return $this->success($response->toArray());
    }
}
