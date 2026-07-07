<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Payment\StoreRequest;
use App\Api\User\Responses\Payment\PaymentResponse;
use App\Modules\Payment\Services\PaymentGatewayInterface;
use App\Modules\Payment\Services\PaymentService;
use App\Modules\User\Models\User;
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
        $user = $this->resolveUser($request);
        $channel = (string) $request->input(StoreRequest::getChannel);
        $channelInt = self::CHANNEL_MAP[$channel] ?? 1;

        $payment = $this->paymentService->createPayment([
            'payment_no' => $this->generatePaymentNo(),
            'order_id' => (int) $request->input(StoreRequest::getOrderId),
            'user_id' => $user->id,
            'amount' => (int) $request->input(StoreRequest::getAmount),
            'channel' => $channelInt,
            'status' => 0,
            'client_ip' => $request->ip(),
            'expired_at' => now()->addMinutes(30),
        ]);

        $gatewayResult = $this->gateway->pay([
            'payment_no' => $payment->payment_no,
            'order_id' => $payment->order_id,
            'amount' => $payment->amount,
            'channel' => $channel,
            'description' => $request->input(StoreRequest::getDescription),
        ]);

        if ($gatewayResult['success'] ?? false) {
            $payment->transaction_id = $gatewayResult['third_party_no'] ?? null;
            $payment->save();
        }

        return $this->success($this->buildPaymentResponse($payment, $channel, $gatewayResult['prepay_data'] ?? null)->toArray());
    }

    #[OA\Get(path: '/payments/{id}', security: [['bearerAuth' => []]], summary: '支付详情', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: '支付记录ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PaymentResponse::class))]
    public function show(int $id, Request $request): JsonResponse
    {
        $user = $this->resolveUser($request);
        $payment = $this->paymentService->findByIdAndUserId($id, $user->id);

        if ($payment === null) {
            return $this->error('支付记录不存在');
        }

        $channelStr = array_search($payment->channel, self::CHANNEL_MAP) ?: 'wechat';

        return $this->success($this->buildPaymentResponse($payment, $channelStr)->toArray());
    }

    private function generatePaymentNo(): string
    {
        return 'PAY'.date('YmdHis').str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
    }

    private function buildPaymentResponse($payment, string $channel, ?array $prepayData = null): PaymentResponse
    {
        $response = new PaymentResponse;
        $response->setId($payment->id);
        $response->setPaymentNo($payment->payment_no);
        $response->setOrderId($payment->order_id);
        $response->setAmount($payment->amount);
        $response->setChannel($channel);
        $response->setStatus($payment->status);
        $response->setThirdPartyNo($payment->transaction_id);
        $response->setPrepayData($prepayData);
        $response->setCreatedAt($payment->created_at->toDateTimeString());

        return $response;
    }

    private function resolveUser(Request $request): User
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401, '未登录');
        }

        return $user;
    }
}
