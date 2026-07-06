<?php

declare(strict_types=1);

namespace App\Modules\Payment\Services;

class MockPaymentGateway implements PaymentGatewayInterface
{
    public function pay(array $params): array
    {
        return [
            'success' => true,
            'third_party_no' => 'MOCK_'.uniqid(),
            'prepay_data' => [
                'appId' => 'mock_app_id',
                'timeStamp' => (string) time(),
                'nonceStr' => bin2hex(random_bytes(16)),
                'package' => 'prepay_id=mock_prepay_'.uniqid(),
                'signType' => 'RSA',
                'paySign' => 'mock_sign_'.bin2hex(random_bytes(16)),
            ],
            'status' => 'pending',
            'message' => 'mock payment initiated',
        ];
    }

    public function query(string $paymentNo): array
    {
        return [
            'success' => true,
            'payment_no' => $paymentNo,
            'third_party_no' => 'MOCK_'.uniqid(),
            'status' => 'paid',
            'paid_at' => now()->toDateTimeString(),
            'amount' => 100,
            'message' => 'mock query success',
        ];
    }

    public function refund(array $params): array
    {
        return [
            'success' => true,
            'refund_no' => 'REFUND_'.uniqid(),
            'payment_no' => $params['payment_no'] ?? '',
            'status' => 'refunded',
            'refund_amount' => $params['refund_amount'] ?? 0,
            'message' => 'mock refund success',
        ];
    }

    public function notify(array $data): array
    {
        $paymentNo = $data['out_trade_no'] ?? ($data['orderId'] ?? '');
        $thirdPartyNo = $data['transaction_id'] ?? ($data['trade_no'] ?? ($data['queryId'] ?? 'MOCK_'.uniqid()));
        $amount = $data['total_fee'] ?? ($data['txnAmt'] ?? 0);

        return [
            'success' => true,
            'payment_no' => $paymentNo,
            'third_party_no' => $thirdPartyNo,
            'status' => 'paid',
            'paid_at' => now()->toDateTimeString(),
            'amount' => $amount,
            'message' => 'mock notify processed',
        ];
    }
}
