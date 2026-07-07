<?php

declare(strict_types=1);

namespace Tests\Feature\Api\User;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CouponTest extends TestCase
{
    use RefreshDatabase;

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

    private function createCoupon(array $overrides = []): int
    {
        return DB::table('coupons')->insertGetId(array_merge([
            'merchant_id' => 1,
            'name' => 'Test Coupon',
            'type' => 1,
            'scope' => 1,
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

    public function test_it_lists_available_coupons(): void
    {
        $token = $this->loginAndGetToken();
        $couponId = $this->createCoupon();

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user/coupons');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.id', $couponId)
            ->assertJsonPath('data.items.0.isReceived', 0);
    }

    public function test_it_receives_available_coupon(): void
    {
        $token = $this->loginAndGetToken();
        $couponId = $this->createCoupon(['limit_per_user' => 2]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/coupons/receive', [
                'coupon_id' => $couponId,
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.couponId', $couponId);

        $this->assertDatabaseHas('user_coupons', [
            'coupon_id' => $couponId,
            'status' => 0,
        ]);
        $this->assertDatabaseHas('coupons', [
            'id' => $couponId,
            'remaining_quantity' => 99,
        ]);
    }

    public function test_it_rejects_receiving_coupon_over_limit(): void
    {
        $token = $this->loginAndGetToken();
        $couponId = $this->createCoupon(['limit_per_user' => 1]);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/coupons/receive', ['coupon_id' => $couponId])
            ->assertStatus(200)
            ->assertJson(['code' => 0]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/coupons/receive', ['coupon_id' => $couponId]);

        $response->assertStatus(200)
            ->assertJson(['code' => 50001]);
    }

    public function test_it_rejects_receiving_inactive_coupon(): void
    {
        $token = $this->loginAndGetToken();
        $couponId = $this->createCoupon(['status' => 0]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/coupons/receive', ['coupon_id' => $couponId]);

        $response->assertStatus(200)
            ->assertJson(['code' => 50001]);
    }

    public function test_it_lists_my_coupons(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();
        $couponId = $this->createCoupon();

        DB::table('user_coupons')->insert([
            'user_id' => $user->id,
            'coupon_id' => $couponId,
            'status' => 0,
            'claim_time' => now(),
            'expire_time' => now()->addDay(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user/coupons/my');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.couponId', $couponId);
    }

    public function test_it_uses_coupon(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();
        $couponId = $this->createCoupon([
            'threshold_amount' => 1000,
            'discount_amount' => 500,
        ]);

        $userCouponId = DB::table('user_coupons')->insertGetId([
            'user_id' => $user->id,
            'coupon_id' => $couponId,
            'status' => 0,
            'claim_time' => now(),
            'expire_time' => now()->addDay(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/coupons/'.$userCouponId.'/use', [
                'order_amount' => 1500,
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertDatabaseHas('user_coupons', [
            'id' => $userCouponId,
            'status' => 1,
        ]);
    }

    public function test_it_rejects_using_coupon_below_threshold(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();
        $couponId = $this->createCoupon([
            'threshold_amount' => 1000,
            'discount_amount' => 500,
        ]);

        $userCouponId = DB::table('user_coupons')->insertGetId([
            'user_id' => $user->id,
            'coupon_id' => $couponId,
            'status' => 0,
            'claim_time' => now(),
            'expire_time' => now()->addDay(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/coupons/'.$userCouponId.'/use', [
                'order_amount' => 500,
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 50001]);
    }

    public function test_it_requires_authentication(): void
    {
        $this->getJson('/api/user/coupons')->assertStatus(401);
        $this->postJson('/api/user/coupons/receive')->assertStatus(401);
        $this->getJson('/api/user/coupons/my')->assertStatus(401);
        $this->postJson('/api/user/coupons/1/use')->assertStatus(401);
    }
}
