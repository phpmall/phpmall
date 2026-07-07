<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Seller;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Juling\Auth\Authentication;
use Tests\TestCase;

class CouponTest extends TestCase
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

    private function createCoupon(int $merchantId = 1, array $overrides = []): int
    {
        return DB::table('coupons')->insertGetId(array_merge([
            'merchant_id' => $merchantId,
            'name' => 'Test Coupon',
            'type' => 1,
            'scope' => 4,
            'threshold_amount' => 1000,
            'discount_amount' => 500,
            'discount_rate' => 0,
            'max_discount_amount' => 0,
            'total_quantity' => 100,
            'remaining_quantity' => 100,
            'limit_per_user' => 1,
            'start_time' => now()->subDay(),
            'end_time' => now()->addDay(),
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }

    private function validCouponPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'New Coupon',
            'type' => 1,
            'amount' => 500,
            'min_order_amount' => 1000,
            'total_quantity' => 100,
            'limit_per_user' => 1,
            'start_time' => now()->subDay()->toDateTimeString(),
            'end_time' => now()->addDay()->toDateTimeString(),
        ], $overrides);
    }

    public function test_it_lists_coupons_for_current_merchant(): void
    {
        $this->withSellerAuth();
        $couponA = $this->createCoupon();
        $this->createCoupon(999);

        $response = $this->getJson('/api/seller/coupons');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.id', $couponA)
            ->assertJsonPath('data.pagination.total', 1);
    }

    public function test_it_filters_coupons_by_status(): void
    {
        $this->withSellerAuth();
        $couponA = $this->createCoupon(1, ['status' => 1]);
        $this->createCoupon(1, ['status' => 0]);

        $response = $this->getJson('/api/seller/coupons?status=1');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.id', $couponA);
    }

    public function test_it_creates_coupon_for_merchant(): void
    {
        $this->withSellerAuth();

        $response = $this->postJson('/api/seller/coupons', $this->validCouponPayload());

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.name', 'New Coupon')
            ->assertJsonPath('data.status', 1);

        $this->assertDatabaseHas('coupons', [
            'merchant_id' => 1,
            'name' => 'New Coupon',
            'type' => 1,
            'discount_amount' => 500,
        ]);
    }

    public function test_it_creates_discount_coupon(): void
    {
        $this->withSellerAuth();

        $response = $this->postJson('/api/seller/coupons', $this->validCouponPayload([
            'type' => 2,
            'amount' => 950,
        ]));

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.type', 2)
            ->assertJsonPath('data.amount', 950);

        $this->assertDatabaseHas('coupons', [
            'merchant_id' => 1,
            'type' => 2,
        ]);
    }

    public function test_it_shows_coupon_detail(): void
    {
        $this->withSellerAuth();
        $couponId = $this->createCoupon();

        $response = $this->getJson('/api/seller/coupons/'.$couponId);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.id', $couponId);
    }

    public function test_it_returns_404_for_other_merchant_coupon(): void
    {
        $this->withSellerAuth();
        $couponId = $this->createCoupon(999);

        $response = $this->getJson('/api/seller/coupons/'.$couponId);

        $response->assertStatus(200)
            ->assertJson(['code' => 404]);
    }

    public function test_it_updates_coupon(): void
    {
        $this->withSellerAuth();
        $couponId = $this->createCoupon();

        $response = $this->putJson('/api/seller/coupons/'.$couponId, $this->validCouponPayload([
            'name' => 'Updated Coupon',
            'status' => 0,
        ]));

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.name', 'Updated Coupon')
            ->assertJsonPath('data.status', 0);
    }

    public function test_it_deletes_coupon(): void
    {
        $this->withSellerAuth();
        $couponId = $this->createCoupon();

        $response = $this->deleteJson('/api/seller/coupons/'.$couponId);

        $response->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertDatabaseMissing('coupons', ['id' => $couponId]);
    }

    public function test_it_enables_and_disables_coupon(): void
    {
        $this->withSellerAuth();
        $couponId = $this->createCoupon(1, ['status' => 0]);

        $this->postJson('/api/seller/coupons/'.$couponId.'/enable')
            ->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertDatabaseHas('coupons', [
            'id' => $couponId,
            'status' => 1,
        ]);

        $this->postJson('/api/seller/coupons/'.$couponId.'/disable')
            ->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertDatabaseHas('coupons', [
            'id' => $couponId,
            'status' => 0,
        ]);
    }

    public function test_it_returns_coupon_stats(): void
    {
        $this->withSellerAuth();
        $couponId = $this->createCoupon();

        DB::table('user_coupons')->insert([
            'user_id' => 1,
            'coupon_id' => $couponId,
            'status' => 1,
            'claim_time' => now(),
            'expire_time' => now()->addDay(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson('/api/seller/coupons/'.$couponId.'/stats');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.couponId', $couponId)
            ->assertJsonPath('data.totalUsed', 1);
    }

    public function test_it_requires_authentication(): void
    {
        $this->getJson('/api/seller/coupons')->assertStatus(401);
        $this->postJson('/api/seller/coupons')->assertStatus(401);
        $this->getJson('/api/seller/coupons/1')->assertStatus(401);
        $this->putJson('/api/seller/coupons/1')->assertStatus(401);
        $this->deleteJson('/api/seller/coupons/1')->assertStatus(401);
    }
}
