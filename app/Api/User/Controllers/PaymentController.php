<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Payment\StoreRequest;
use App\Api\User\Responses\Payment\PaymentResponse;
use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Services\PaymentGatewayInterface;
use App\Modules\Payment\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class PaymentController extends BaseController
{
    private const array CHANNEL_MAP = [
        'wechat' => 1,
        'alipay' => 2,
        'unionpay' => 4,
    ];

    public function __construct(
        private readonly PaymentService $paymentService,
        private readonly PaymentGatewayInterface $gateway,
    ) {
        parent::__construct();
    }

    #[OA\Post(path: '/payments', security: [['bearerAuth' => []]], summary: '创建支付', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: StoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PaymentResponse::class))]
    public function store(StoreRequest $request): JsonResponse
    {
        $user = $request->user();
        $userId = $user ? $user->id : 0;
        $paymentNo = 'PAY'.date('YmdHis').str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
        $channel = $request->input(StoreRequest::getChannel);
        $channelInt = self::CHANNEL_MAP[$channel] ?? 1;

        $payment = Payment::create([
            'payment_no' => $paymentNo,
            'order_id' => $request->input(StoreRequest::getOrderId),
            'user_id' => $userId,
            'amount' => $request->input(StoreRequest::getAmount),
            'channel' => $channelInt,
            'status' => 0,
            'client_ip' => $request->ip(),
            'expired_at' => now()->addMinutes(30),
        ]);

        $gatewayResult = $this->gateway->pay([
            'payment_no' => $paymentNo,
            'order_id' => $payment->order_id,
            'amount' => $payment->amount,
            'channel' => $channel,
            'description' => $request->input(StoreRequest::getDescription),
        ]);

        if ($gatewayResult['success'] ?? false) {
            $payment->transaction_id = $gatewayResult['third_party_no'] ?? null;
            $payment->save();
        }

        $response = new PaymentResponse;
        $response->setId($payment->id);
        $response->setPaymentNo($payment->payment_no);
        $response->setOrderId($payment->order_id);
        $response->setAmount($payment->amount);
        $response->setChannel($channel);
        $response->setStatus($payment->status);
        $response->setThirdPartyNo($payment->transaction_id);
        $response->setPrepayData($gatewayResult['prepay_data'] ?? null);
        $response->setCreatedAt($payment->created_at->toDateTimeString());

        return $this->success($response->toArray());
    }

    #[OA\Get(path: '/payments/{id}', security: [['bearerAuth' => []]], summary: '支付详情', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: '支付记录ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PaymentResponse::class))]
    public function show(int $id, Request $request): JsonResponse
    {
        $user = $request->user();
        $userId = $user ? $user->id : 0;
        $payment = Payment::where('id', $id)->where('user_id', $userId)->first();

        if (! $payment) {
            return $this->error('支付记录不存在');
        }

        $channelStr = array_search($payment->channel, self::CHANNEL_MAP) ?: 'wechat';

        $response = new PaymentResponse;
        $response->setId($payment->id);
        $response->setPaymentNo($payment->payment_no);
        $response->setOrderId($payment->order_id);
        $response->setAmount($payment->amount);
        $response->setChannel($channelStr);
        $response->setStatus($payment->status);
        $response->setThirdPartyNo($payment->transaction_id);
        $response->setPrepayData(null);
        $response->setCreatedAt($payment->created_at->toDateTimeString());

        return $this->success($response->toArray());
    }
}
