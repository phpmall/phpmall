<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Seller;

use App\Modules\Auth\Models\Permission;
use App\Modules\Merchant\Models\MerchantStaff;
use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Juling\Auth\Authentication;
use Tests\TestCase;

class SubAccountTest extends TestCase
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

    private function createStaff(array $overrides = []): int
    {
        $staff = MerchantStaff::create(array_merge([
            'merchant_id' => 1,
            'username' => 'staff_'.uniqid(),
            'password_hash' => Hash::make('password'),
            'real_name' => 'Test Staff',
            'phone' => '13800138000',
            'status' => 1,
        ], $overrides));

        return $staff->id;
    }

    private function createPermission(array $overrides = []): int
    {
        $permission = Permission::create(array_merge([
            'name' => 'permission_'.uniqid(),
            'display_name' => 'Test Permission',
            'description' => 'Test permission description',
            'parent_id' => 0,
            'type' => 'api',
            'route' => '/api/test',
            'icon' => null,
            'sort' => 0,
            'status' => 1,
        ], $overrides));

        return $permission->id;
    }

    public function test_it_shows_sub_account_detail(): void
    {
        $this->withSellerAuth();
        $staffId = $this->createStaff(['real_name' => 'Detail Staff']);

        $response = $this->getJson("/api/seller/sub-accounts/{$staffId}");

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.id', $staffId)
            ->assertJsonPath('data.realName', 'Detail Staff')
            ->assertJsonPath('data.username', MerchantStaff::find($staffId)->username);
    }

    public function test_it_returns_404_for_other_merchant_sub_account(): void
    {
        $this->withSellerAuth();
        $staffId = $this->createStaff(['merchant_id' => 999]);

        $response = $this->getJson("/api/seller/sub-accounts/{$staffId}");

        $response->assertStatus(200)
            ->assertJson(['code' => 404]);
    }

    public function test_it_updates_sub_account_info(): void
    {
        $this->withSellerAuth();
        $staffId = $this->createStaff();

        $response = $this->putJson("/api/seller/sub-accounts/{$staffId}", [
            'real_name' => 'Updated Name',
            'phone' => '13900139000',
            'email' => 'updated@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.realName', 'Updated Name')
            ->assertJsonPath('data.phone', '13900139000');

        $this->assertDatabaseHas('merchant_staffs', [
            'id' => $staffId,
            'real_name' => 'Updated Name',
            'phone' => '13900139000',
        ]);
    }

    public function test_it_updates_sub_account_password(): void
    {
        $this->withSellerAuth();
        $staffId = $this->createStaff();

        $response = $this->putJson("/api/seller/sub-accounts/{$staffId}", [
            'password' => 'newpassword123',
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertTrue(Hash::check('newpassword123', MerchantStaff::find($staffId)->password_hash));
    }

    public function test_it_returns_404_when_updating_other_merchant_sub_account(): void
    {
        $this->withSellerAuth();
        $staffId = $this->createStaff(['merchant_id' => 999]);

        $response = $this->putJson("/api/seller/sub-accounts/{$staffId}", [
            'real_name' => 'Hacker',
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 404]);
    }

    public function test_it_deletes_sub_account(): void
    {
        $this->withSellerAuth();
        $staffId = $this->createStaff();

        $response = $this->deleteJson("/api/seller/sub-accounts/{$staffId}");

        $response->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertSoftDeleted('merchant_staffs', ['id' => $staffId]);
    }

    public function test_it_returns_404_when_deleting_other_merchant_sub_account(): void
    {
        $this->withSellerAuth();
        $staffId = $this->createStaff(['merchant_id' => 999]);

        $response = $this->deleteJson("/api/seller/sub-accounts/{$staffId}");

        $response->assertStatus(200)
            ->assertJson(['code' => 404]);
    }

    public function test_it_assigns_permissions_to_sub_account(): void
    {
        $this->withSellerAuth();
        $staffId = $this->createStaff();
        $permissionA = $this->createPermission();
        $permissionB = $this->createPermission();

        $response = $this->postJson("/api/seller/sub-accounts/{$staffId}/permissions", [
            'permission_ids' => [$permissionA, $permissionB],
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.permission_ids', [$permissionA, $permissionB]);

        $this->assertDatabaseHas('model_has_permissions', [
            'model_type' => MerchantStaff::class,
            'model_id' => $staffId,
            'permission_id' => $permissionA,
        ]);
        $this->assertDatabaseHas('model_has_permissions', [
            'model_type' => MerchantStaff::class,
            'model_id' => $staffId,
            'permission_id' => $permissionB,
        ]);
    }

    public function test_it_replaces_existing_permissions_on_assign(): void
    {
        $this->withSellerAuth();
        $staffId = $this->createStaff();
        $permissionA = $this->createPermission();
        $permissionB = $this->createPermission();
        $permissionC = $this->createPermission();

        $staff = MerchantStaff::find($staffId);
        $staff->permissions()->sync([$permissionA => ['model_type' => MerchantStaff::class]]);

        $response = $this->postJson("/api/seller/sub-accounts/{$staffId}/permissions", [
            'permission_ids' => [$permissionB, $permissionC],
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertDatabaseMissing('model_has_permissions', [
            'model_type' => MerchantStaff::class,
            'model_id' => $staffId,
            'permission_id' => $permissionA,
        ]);
        $this->assertDatabaseCount('model_has_permissions', 2);
    }

    public function test_it_validates_permission_ids(): void
    {
        $this->withSellerAuth();
        $staffId = $this->createStaff();

        $response = $this->postJson("/api/seller/sub-accounts/{$staffId}/permissions", [
            'permission_ids' => [99999],
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('code', 422)
            ->assertJson(['message' => ['permission_ids.0' => ['权限不存在']]]);
    }

    public function test_it_returns_404_when_assigning_permissions_to_other_merchant_sub_account(): void
    {
        $this->withSellerAuth();
        $staffId = $this->createStaff(['merchant_id' => 999]);
        $permission = $this->createPermission();

        $response = $this->postJson("/api/seller/sub-accounts/{$staffId}/permissions", [
            'permission_ids' => [$permission],
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 404]);
    }
}
