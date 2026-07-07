<?php

declare(strict_types=1);

namespace Tests\Feature\Api\User;

use App\Modules\Payment\Models\Payment;
use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Redis::connection()->flushall();
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'phone' => '13800138000',
            'password' => Hash::make('password123'),
        ]);
    }

    private function loginAndGetToken(): string
    {
        $user = $this->createUser();

        $response = $this->postJson('/api/user/auth/login', [
            'mobile' => '13800138000',
            'password' => 'password123',
            'captcha' => 'test',
            'uuid' => 'test-uuid',
            'device_name' => 'test-device',
        ]);

        return $response->json('data.access_token');
    }

    public function test_store_creates_payment_and_returns_prepay_data(): void
    {
        $token = $this->loginAndGetToken();

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/payments', [
                'order_id' => 1,
                'amount' => 10000,
                'channel' => 'wechat',
                'description' => 'test payment',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'data' => [
                    'id',
                    'paymentNo',
                    'orderId',
                    'amount',
                    'channel',
                    'status',
                    'thirdPartyNo',
                    'prepayData' => [
                        'appId',
                        'timeStamp',
                        'nonceStr',
                        'package',
                        'signType',
                        'paySign',
                    ],
                    'createdAt',
                ],
            ])
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.orderId', 1)
            ->assertJsonPath('data.amount', 10000)
            ->assertJsonPath('data.channel', 'wechat')
            ->assertJsonPath('data.status', 0);

        $this->assertNotEmpty($response->json('data.paymentNo'));
        $this->assertStringStartsWith('MOCK_', $response->json('data.thirdPartyNo'));
    }

    public function test_store_validates_required_fields(): void
    {
        $token = $this->loginAndGetToken();

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/payments', []);

        $response->assertStatus(422);
    }

    public function test_store_validates_channel(): void
    {
        $token = $this->loginAndGetToken();

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/payments', [
                'order_id' => 1,
                'amount' => 10000,
                'channel' => 'invalid',
            ]);

        $response->assertStatus(422);
    }

    public function test_show_returns_payment_by_id(): void
    {
        $user = $this->createUser();

        $loginResponse = $this->postJson('/api/user/auth/login', [
            'mobile' => '13800138000',
            'password' => 'password123',
            'captcha' => 'test',
            'uuid' => 'test-uuid',
            'device_name' => 'test-device',
        ]);

        $token = $loginResponse->json('data.access_token');

        $payment = Payment::create([
            'payment_no' => 'PAY20240101000001',
            'order_id' => 1,
            'user_id' => $user->id,
            'amount' => 5000,
            'channel' => 2,
            'status' => 0,
            'expired_at' => now()->addMinutes(30),
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user/payments/'.$payment->id);

        $response->assertStatus(200);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'data' => [
                    'id',
                    'paymentNo',
                    'orderId',
                    'amount',
                    'channel',
                    'status',
                    'createdAt',
                ],
            ])
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.id', $payment->id)
            ->assertJsonPath('data.paymentNo', 'PAY20240101000001')
            ->assertJsonPath('data.amount', 5000)
            ->assertJsonPath('data.channel', 'alipay');
    }

    public function test_show_returns_404_for_nonexistent_payment(): void
    {
        $token = $this->loginAndGetToken();

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user/payments/99999');

        $response->assertStatus(200)
            ->assertJson(['code' => 50001]);
    }

    public function test_show_returns_404_for_other_users_payment(): void
    {
        $userA = User::factory()->create([
            'phone' => '13800138001',
            'password' => Hash::make('password123'),
        ]);

        $payment = Payment::create([
            'payment_no' => 'PAY20240101000002',
            'order_id' => 1,
            'user_id' => $userA->id,
            'amount' => 5000,
            'channel' => 2,
            'status' => 0,
            'expired_at' => now()->addMinutes(30),
        ]);

        $token = $this->loginAndGetToken();

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user/payments/'.$payment->id);

        $response->assertStatus(200)
            ->assertJson(['code' => 50001]);
    }

    public function test_store_requires_authentication(): void
    {
        $response = $this->postJson('/api/user/payments', [
            'order_id' => 1,
            'amount' => 10000,
            'channel' => 'wechat',
        ]);

        $response->assertStatus(401);
    }

    public function test_show_requires_authentication(): void
    {
        $response = $this->getJson('/api/user/payments/1');

        $response->assertStatus(401);
    }
}
