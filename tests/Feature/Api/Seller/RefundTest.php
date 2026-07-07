<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Seller;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Juling\Auth\Authentication;
use Tests\TestCase;

class RefundTest extends TestCase
{
    use RefreshDatabase;

    private function withSellerAuth(int $merchantId = 1): void
    {
        $user = User::factory()->create();
        $token = (new Authentication)->createToken([
            'sub' => $user->id,
            'merchant_id' => $merchantId,
            'type' => 'merchant_staff',
            'iat' => now()->timestamp,
            'exp' => now()->addHour()->timestamp,
        ]);

        $this->withHeader('Authorization', 'Bearer '.$token);
    }

    private function createOrder(int $userId = 1, int $merchantId = 1, array $overrides = []): int
    {
        return DB::table('orders')->insertGetId(array_merge([
            'order_no' => 'O'.now()->format('YmdHis').random_int(1000, 9999),
            'user_id' => $userId,
            'merchant_id' => $merchantId,
            'parent_order_id' => null,
            'order_type' => 1,
            'status' => 20,
            'pay_status' => 20,
            'refund_status' => 10,
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

    public function test_it_lists_refunds_for_current_merchant(): void
    {
        $this->withSellerAuth();
        $orderA = $this->createOrder(1, 1, ['order_no' => 'OA1234567890']);
        $this->createRefund(1, $orderA, 1);
        $orderB = $this->createOrder(1, 999, ['order_no' => 'OB1234567890']);
        $this->createRefund(1, $orderB, 999);

        $response = $this->getJson('/api/seller/refunds');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.pagination.total', 1);
    }

    public function test_it_filters_refunds_by_status(): void
    {
        $this->withSellerAuth();
        $orderA = $this->createOrder(1, 1, ['order_no' => 'OA1234567891']);
        $orderB = $this->createOrder(1, 1, ['order_no' => 'OB1234567891']);
        $this->createRefund(1, $orderA, 1, ['status' => 0]);
        $this->createRefund(1, $orderB, 1, ['status' => 1]);

        $response = $this->getJson('/api/seller/refunds?status=1');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.pagination.total', 1)
            ->assertJsonPath('data.items.0.status', 1);
    }

    public function test_it_shows_refund_detail(): void
    {
        $this->withSellerAuth();
        $orderId = $this->createOrder(1, 1, ['order_no' => 'OA1234567892']);
        $refundId = $this->createRefund(1, $orderId, 1);

        $response = $this->getJson('/api/seller/refunds/'.$refundId);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.id', $refundId)
            ->assertJsonPath('data.status', 0);
    }

    public function test_it_returns_404_for_other_merchant_refund(): void
    {
        $this->withSellerAuth();
        $orderId = $this->createOrder(1, 999);
        $refundId = $this->createRefund(1, $orderId, 999);

        $response = $this->getJson('/api/seller/refunds/'.$refundId);

        $response->assertStatus(200)
            ->assertJson(['code' => 404]);
    }

    public function test_it_approves_refund(): void
    {
        $this->withSellerAuth();
        $orderId = $this->createOrder(1, 1);
        $refundId = $this->createRefund(1, $orderId, 1);

        $response = $this->postJson('/api/seller/refunds/'.$refundId.'/audit', [
            'status' => 1,
            'remark' => '同意退款',
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.message', '审核成功');

        $this->assertDatabaseHas('order_refunds', [
            'id' => $refundId,
            'status' => 1,
            'refund_amount' => 10500,
            'merchant_remark' => '同意退款',
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'refund_status' => 20,
        ]);
    }

    public function test_it_rejects_refund(): void
    {
        $this->withSellerAuth();
        $orderId = $this->createOrder(1, 1);
        $refundId = $this->createRefund(1, $orderId, 1);

        $response = $this->postJson('/api/seller/refunds/'.$refundId.'/audit', [
            'status' => 2,
            'remark' => '拒绝原因',
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.message', '审核成功');

        $this->assertDatabaseHas('order_refunds', [
            'id' => $refundId,
            'status' => 2,
            'merchant_remark' => '拒绝原因',
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'refund_status' => 40,
        ]);
    }

    public function test_it_rejects_auditing_non_pending_refund(): void
    {
        $this->withSellerAuth();
        $orderId = $this->createOrder(1, 1);
        $refundId = $this->createRefund(1, $orderId, 1, ['status' => 1]);

        $response = $this->postJson('/api/seller/refunds/'.$refundId.'/audit', [
            'status' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 50001]);
    }

    public function test_it_requires_authentication(): void
    {
        $this->getJson('/api/seller/refunds')->assertStatus(401);
        $this->getJson('/api/seller/refunds/1')->assertStatus(401);
        $this->postJson('/api/seller/refunds/1/audit')->assertStatus(401);
    }
}
