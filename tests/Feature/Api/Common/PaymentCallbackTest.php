<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Common;

use App\Modules\Payment\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentCallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_wechat_notify_updates_payment_status(): void
    {
        $payment = Payment::create([
            'payment_no' => 'PAY20240101000001',
            'order_id' => 1,
            'user_id' => 1,
            'amount' => 10000,
            'channel' => 1,
            'status' => 0,
            'expired_at' => now()->addMinutes(30),
        ]);

        $response = $this->postJson('/api/common/wechat/notify', [
            'out_trade_no' => $payment->payment_no,
            'transaction_id' => 'WX20240101000001',
            'trade_state' => 'SUCCESS',
            'total_fee' => 10000,
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
        $this->assertNotNull($payment->transaction_id);
    }

    public function test_alipay_notify_updates_payment_status(): void
    {
        $payment = Payment::create([
            'payment_no' => 'PAY20240101000002',
            'order_id' => 2,
            'user_id' => 1,
            'amount' => 20000,
            'channel' => 2,
            'status' => 0,
            'expired_at' => now()->addMinutes(30),
        ]);

        $response = $this->postJson('/api/common/alipay/notify', [
            'out_trade_no' => $payment->payment_no,
            'trade_no' => 'ALI20240101000001',
            'trade_status' => 'TRADE_SUCCESS',
            'total_amount' => '200.00',
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.success', true);

        $payment->refresh();
        $this->assertEquals(1, $payment->status);
    }

    public function test_unionpay_notify_updates_payment_status(): void
    {
        $payment = Payment::create([
            'payment_no' => 'PAY20240101000003',
            'order_id' => 3,
            'user_id' => 1,
            'amount' => 30000,
            'channel' => 4,
            'status' => 0,
            'expired_at' => now()->addMinutes(30),
        ]);

        $response = $this->postJson('/api/common/unionpay/notify', [
            'orderId' => $payment->payment_no,
            'queryId' => 'UP20240101000001',
            'respCode' => '00',
            'txnAmt' => '30000',
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.success', true);

        $payment->refresh();
        $this->assertEquals(1, $payment->status);
    }

    public function test_notify_is_idempotent_for_already_paid_payment(): void
    {
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
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.success', true);

        $payment->refresh();
        $this->assertEquals(1, $payment->status);
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
}
