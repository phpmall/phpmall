<?php

declare(strict_types=1);

namespace App\Modules\Payment\Services;

interface PaymentGatewayInterface
{
    /**
     * 发起支付请求
     *
     * @param  array  $params  支付参数 (order_id, amount, channel, description, etc.)
     * @return array 支付结果 (third_party_no, prepay_data, status, etc.)
     */
    public function pay(array $params): array;

    /**
     * 查询支付订单状态
     *
     * @param  string  $paymentNo  支付单号
     * @return array 查询结果 (status, third_party_no, paid_at, etc.)
     */
    public function query(string $paymentNo): array;

    /**
     * 发起退款请求
     *
     * @param  array  $params  退款参数 (payment_no, refund_amount, reason, etc.)
     * @return array 退款结果 (refund_no, status, etc.)
     */
    public function refund(array $params): array;

    /**
     * 处理支付回调通知
     *
     * @param  array  $data  回调数据
     * @return array 处理结果 (success, payment_no, status, etc.)
     */
    public function notify(array $data): array;
}
