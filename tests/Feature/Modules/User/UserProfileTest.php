<?php

namespace Tests\Feature\Modules\User;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserProfileTest extends TestCase
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

    public function test_user_can_fetch_profile(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/user/profile');

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonPath('data.id', $this->user->id);
    }

    public function test_user_can_update_profile(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->putJson('/api/user/profile', [
                'nickname' => 'Updated Nickname',
                'avatar' => 'https://example.com/avatar.png',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonPath('data.nickname', 'Updated Nickname')
            ->assertJsonPath('data.avatar', 'https://example.com/avatar.png');

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'nickname' => 'Updated Nickname',
        ]);
    }
}
