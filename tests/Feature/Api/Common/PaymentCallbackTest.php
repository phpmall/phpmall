<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Common;

use App\Events\OrderPaid;
use App\Modules\Order\Models\Order;
use App\Modules\Payment\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class PaymentCallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_wechat_notify_updates_payment_and_order_status(): void
    {
        Event::fake([OrderPaid::class]);

        $order = $this->createOrder();
        $payment = $this->createPayment($order->id, 1);

        $response = $this->postJson('/api/common/wechat/notify', [
            'out_trade_no' => $payment->payment_no,
            'transaction_id' => 'WX20240101000001',
            'trade_state' => 'SUCCESS',
            'total_fee' => $payment->amount,
            'sign' => 'mock_sign',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'data' => [
                    'success',
                    'message',
                ],
            ])
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.success', true);

        $payment->refresh();
        $this->assertEquals(1, $payment->status);
        $this->assertNotNull($payment->paid_at);
        $this->assertEquals('WX20240101000001', $payment->transaction_id);
        $this->assertNotNull($payment->notify_raw);

        $order->refresh();
        $this->assertEquals(20, $order->status);
        $this->assertEquals(20, $order->pay_status);
        $this->assertNotNull($order->pay_time);
        $this->assertEquals('WX20240101000001', $order->pay_transaction_id);
        $this->assertEquals(1, $order->pay_method);

        Event::assertDispatched(OrderPaid::class, function (OrderPaid $event) use ($order): bool {
            return $event->orderId === $order->id;
        });
    }

    public function test_alipay_notify_updates_payment_status(): void
    {
        Event::fake([OrderPaid::class]);

        $order = $this->createOrder();
        $payment = $this->createPayment($order->id, 2);

        $response = $this->postJson('/api/common/alipay/notify', [
            'out_trade_no' => $payment->payment_no,
            'trade_no' => 'ALI20240101000001',
            'trade_status' => 'TRADE_SUCCESS',
            'total_amount' => '200.00',
            'sign' => 'mock_sign',
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.success', true);

        $payment->refresh();
        $this->assertEquals(1, $payment->status);

        $order->refresh();
        $this->assertEquals(20, $order->status);
        $this->assertEquals(2, $order->pay_method);

        Event::assertDispatched(OrderPaid::class);
    }

    public function test_unionpay_notify_updates_payment_status(): void
    {
        Event::fake([OrderPaid::class]);

        $order = $this->createOrder();
        $payment = $this->createPayment($order->id, 4);

        $response = $this->postJson('/api/common/unionpay/notify', [
            'orderId' => $payment->payment_no,
            'queryId' => 'UP20240101000001',
            'respCode' => '00',
            'txnAmt' => $payment->amount,
            'sign' => 'mock_sign',
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.success', true);

        $payment->refresh();
        $this->assertEquals(1, $payment->status);

        $order->refresh();
        $this->assertEquals(20, $order->status);
        $this->assertEquals(4, $order->pay_method);

        Event::assertDispatched(OrderPaid::class);
    }

    public function test_notify_is_idempotent_for_already_paid_payment(): void
    {
        Event::fake([OrderPaid::class]);

        $payment = Payment::create([
            'payment_no' => 'PAY20240101000004',
            'order_id' => 4,
            'user_id' => 1,
            'amount' => 10000,
            'channel' => 1,
            'status' => 1,
            'paid_at' => now(),
            'expired_at' => now()->addMinutes(30),
        ]);

        $response = $this->postJson('/api/common/wechat/notify', [
            'out_trade_no' => $payment->payment_no,
            'transaction_id' => 'WX20240101000002',
            'trade_state' => 'SUCCESS',
            'total_fee' => 10000,
            'sign' => 'mock_sign',
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.success', true)
            ->assertJsonPath('data.message', 'payment already processed');

        $payment->refresh();
        $this->assertEquals(1, $payment->status);

        Event::assertNotDispatched(OrderPaid::class);
    }

    public function test_notify_returns_success_for_missing_payment(): void
    {
        Event::fake([OrderPaid::class]);

        $response = $this->postJson('/api/common/wechat/notify', [
            'out_trade_no' => 'PAY_NOT_EXISTS',
            'transaction_id' => 'WX20240101000003',
            'trade_state' => 'SUCCESS',
            'total_fee' => 10000,
            'sign' => 'mock_sign',
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.success', true);

        Event::assertNotDispatched(OrderPaid::class);
    }

    public function test_notify_rejects_invalid_signature(): void
    {
        Event::fake([OrderPaid::class]);

        $order = $this->createOrder();
        $payment = $this->createPayment($order->id, 1);

        $response = $this->postJson('/api/common/wechat/notify', [
            'out_trade_no' => $payment->payment_no,
            'transaction_id' => 'WX20240101000001',
            'trade_state' => 'SUCCESS',
            'total_fee' => $payment->amount,
            'sign' => 'invalid_sign',
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.success', false)
            ->assertJsonPath('data.message', 'invalid notify signature');

        $payment->refresh();
        $this->assertEquals(0, $payment->status);

        Event::assertNotDispatched(OrderPaid::class);
    }

    public function test_wechat_notify_validates_required_fields(): void
    {
        $response = $this->postJson('/api/common/wechat/notify', []);

        $response->assertStatus(422);
    }

    public function test_alipay_notify_validates_required_fields(): void
    {
        $response = $this->postJson('/api/common/alipay/notify', []);

        $response->assertStatus(422);
    }

    public function test_unionpay_notify_validates_required_fields(): void
    {
        $response = $this->postJson('/api/common/unionpay/notify', []);

        $response->assertStatus(422);
    }

    private function createOrder(): Order
    {
        return Order::create([
            'order_no' => 'O'.date('YmdHis').str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT),
            'user_id' => 1,
            'merchant_id' => 1,
            'status' => 10,
            'pay_status' => 0,
            'refund_status' => 0,
            'product_amount' => 10000,
            'pay_amount' => 10000,
            'source' => 2,
        ]);
    }

    private function createPayment(int $orderId, int $channel): Payment
    {
        return Payment::create([
            'payment_no' => 'PAY'.date('YmdHis').str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT),
            'order_id' => $orderId,
            'user_id' => 1,
            'amount' => 10000,
            'channel' => $channel,
            'status' => 0,
            'expired_at' => now()->addMinutes(30),
        ]);
    }
}
