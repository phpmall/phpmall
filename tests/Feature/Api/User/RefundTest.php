<?php

declare(strict_types=1);

namespace Tests\Feature\Api\User;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class RefundTest extends TestCase
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
        $this->createUser();

        $response = $this->postJson('/api/user/auth/login', [
            'mobile' => '13800138000',
            'password' => 'password123',
            'captcha' => 'test',
            'uuid' => 'test-uuid',
            'device_name' => 'test-device',
        ]);

        return $response->json('data.access_token');
    }

    private function createOrder(int $userId, int $merchantId = 1, array $overrides = []): int
    {
        return DB::table('orders')->insertGetId(array_merge([
            'order_no' => 'O'.now()->format('YmdHis').random_int(1000, 9999),
            'user_id' => $userId,
            'merchant_id' => $merchantId,
            'parent_order_id' => null,
            'order_type' => 1,
            'status' => 20,
            'pay_status' => 20,
            'refund_status' => 0,
            'product_amount' => 10000,
            'discount_amount' => 0,
            'freight_amount' => 500,
            'pay_amount' => 10500,
            'pay_time' => now(),
            'remark' => null,
            'source' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }

    private function createOrderItem(int $orderId, int $merchantId = 1): int
    {
        return DB::table('order_items')->insertGetId([
            'order_id' => $orderId,
            'product_id' => 1,
            'sku_id' => 1,
            'merchant_id' => $merchantId,
            'product_title' => 'Test Product',
            'product_image' => 'https://example.com/image.jpg',
            'sku_specs' => json_encode([['name' => 'Color', 'value' => 'Red']]),
            'price' => 5000,
            'quantity' => 2,
            'total_amount' => 10000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createRefund(int $userId, int $orderId, int $merchantId = 1, array $overrides = []): int
    {
        return DB::table('order_refunds')->insertGetId(array_merge([
            'refund_no' => 'R'.now()->format('YmdHis').random_int(1000, 9999),
            'order_id' => $orderId,
            'order_item_id' => null,
            'user_id' => $userId,
            'merchant_id' => $merchantId,
            'type' => 1,
            'reason' => '不想要了',
            'reason_type' => 1,
            'description' => null,
            'images' => json_encode([]),
            'apply_amount' => 10500,
            'refund_amount' => 0,
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }

    public function test_it_applies_for_refund(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();
        $orderId = $this->createOrder($user->id);
        $this->createOrderItem($orderId);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/refunds', [
                'order_id' => $orderId,
                'reason' => '不想要了',
                'type' => 'refund',
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.status', 0)
            ->assertJsonPath('data.amount', 10500)
            ->assertJsonPath('data.type', 'refund');

        $this->assertDatabaseHas('order_refunds', [
            'order_id' => $orderId,
            'user_id' => $user->id,
            'status' => 0,
            'apply_amount' => 10500,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'refund_status' => 10,
        ]);
    }

    public function test_it_applies_for_return_refund_with_amount_and_images(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();
        $orderId = $this->createOrder($user->id);
        $this->createOrderItem($orderId);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/refunds', [
                'order_id' => $orderId,
                'reason' => '商品质量问题',
                'type' => 'return_refund',
                'amount' => 5000,
                'images' => ['https://example.com/a.jpg', 'https://example.com/b.jpg'],
                'description' => '描述问题',
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.status', 0)
            ->assertJsonPath('data.amount', 5000)
            ->assertJsonPath('data.type', 'return_refund');

        $this->assertDatabaseHas('order_refunds', [
            'order_id' => $orderId,
            'type' => 2,
            'apply_amount' => 5000,
        ]);
    }

    public function test_it_rejects_refund_for_unpaid_order(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();
        $orderId = $this->createOrder($user->id, 1, ['pay_status' => 0, 'status' => 10]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/refunds', [
                'order_id' => $orderId,
                'reason' => '不想要了',
                'type' => 'refund',
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 50001]);
    }

    public function test_it_rejects_refund_for_other_users_order(): void
    {
        $token = $this->loginAndGetToken();
        $otherUser = User::factory()->create();
        $orderId = $this->createOrder($otherUser->id);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/refunds', [
                'order_id' => $orderId,
                'reason' => '不想要了',
                'type' => 'refund',
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 50001]);
    }

    public function test_it_rejects_refund_amount_exceeding_pay_amount(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();
        $orderId = $this->createOrder($user->id, 1, ['pay_amount' => 1000]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/refunds', [
                'order_id' => $orderId,
                'reason' => '不想要了',
                'type' => 'refund',
                'amount' => 2000,
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 50001]);
    }

    public function test_it_lists_refunds(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();
        $orderA = $this->createOrder($user->id, 1, ['order_no' => 'OA1234567890']);
        $orderB = $this->createOrder($user->id, 1, ['order_no' => 'OB1234567890']);
        $this->createRefund($user->id, $orderA);
        $this->createRefund($user->id, $orderB);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user/refunds');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonCount(2, 'data.items')
            ->assertJsonPath('data.pagination.total', 2);
    }

    public function test_it_filters_refunds_by_status(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();
        $orderA = $this->createOrder($user->id, 1, ['order_no' => 'OA1234567891']);
        $orderB = $this->createOrder($user->id, 1, ['order_no' => 'OB1234567891']);
        $this->createRefund($user->id, $orderA, 1, ['status' => 0]);
        $this->createRefund($user->id, $orderB, 1, ['status' => 1]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user/refunds?status=1');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.pagination.total', 1)
            ->assertJsonPath('data.items.0.status', 1);
    }

    public function test_it_shows_refund_detail(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();
        $orderId = $this->createOrder($user->id, 1, ['order_no' => 'OA1234567892']);
        $refundId = $this->createRefund($user->id, $orderId);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user/refunds/'.$refundId);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.id', $refundId)
            ->assertJsonPath('data.orderId', $orderId)
            ->assertJsonPath('data.status', 0);
    }

    public function test_it_cancels_pending_refund(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();
        $orderId = $this->createOrder($user->id);
        $refundId = $this->createRefund($user->id, $orderId, 1, ['status' => 0]);
        DB::table('orders')->where('id', $orderId)->update(['refund_status' => 10]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/refunds/'.$refundId.'/cancel');

        $response->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertDatabaseHas('order_refunds', [
            'id' => $refundId,
            'status' => 7,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'refund_status' => 0,
        ]);
    }

    public function test_it_rejects_canceling_non_pending_refund(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();
        $orderId = $this->createOrder($user->id);
        $refundId = $this->createRefund($user->id, $orderId, 1, ['status' => 1]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/refunds/'.$refundId.'/cancel');

        $response->assertStatus(200)
            ->assertJson(['code' => 50001]);
    }

    public function test_it_requires_authentication(): void
    {
        $this->getJson('/api/user/refunds')->assertStatus(401);
        $this->postJson('/api/user/refunds')->assertStatus(401);
        $this->getJson('/api/user/refunds/1')->assertStatus(401);
        $this->postJson('/api/user/refunds/1/cancel')->assertStatus(401);
    }
}
