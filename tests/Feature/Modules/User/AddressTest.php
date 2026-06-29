<?php

namespace Tests\Feature\Modules\User;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'phone' => '13800138000',
            'password' => Hash::make('password123'),
        ]);

        $this->token = $this->user->createToken('test')->plainTextToken;
    }

    public function test_user_can_create_address(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson('/api/user/addresses', $this->addressPayload());

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonPath('message', '添加成功')
            ->assertJsonPath('data.contact_name', '张三');

        $this->assertDatabaseHas('user_addresses', [
            'user_id' => $this->user->id,
            'contact_name' => '张三',
        ]);
    }

    public function test_user_can_list_addresses(): void
    {
        $this->user->addresses()->create($this->addressPayload());

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/user/addresses');

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonCount(1, 'data.list')
            ->assertJsonPath('data.total', 1);
    }

    public function test_user_can_update_address(): void
    {
        $address = $this->user->addresses()->create($this->addressPayload());

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->putJson("/api/user/addresses/{$address->id}", [
                ...$this->addressPayload(),
                'contact_name' => '李四',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonPath('data.contact_name', '李四');
    }

    public function test_user_can_delete_address(): void
    {
        $address = $this->user->addresses()->create($this->addressPayload());

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->deleteJson("/api/user/addresses/{$address->id}");

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonPath('message', '删除成功');

        $this->assertDatabaseMissing('user_addresses', [
            'id' => $address->id,
        ]);
    }

    public function test_user_cannot_access_other_users_address(): void
    {
        $otherUser = User::factory()->create([
            'phone' => '13900139000',
            'password' => Hash::make('password123'),
        ]);

        $address = $otherUser->addresses()->create($this->addressPayload());

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson("/api/user/addresses/{$address->id}");

        $response->assertStatus(404);
    }

    /**
     * @return array<string, mixed>
     */
    private function addressPayload(): array
    {
        return [
            'contact_name' => '张三',
            'contact_phone' => '13800138000',
            'province' => '广东省',
            'city' => '深圳市',
            'district' => '南山区',
            'detail' => '科技园南路 123 号',
            'zip_code' => '518000',
            'is_default' => 1,
        ];
    }
}
