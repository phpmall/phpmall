<?php

namespace Tests\Feature\Modules\Auth;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_phone(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'phone' => '13800138000',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nickname' => 'Test User',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonPath('message', '注册成功')
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'phone', 'nickname'],
                    'token',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'phone' => '13800138000',
            'nickname' => 'Test User',
        ]);
    }

    public function test_user_can_login_with_phone(): void
    {
        User::factory()->create([
            'phone' => '13800138000',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'phone' => '13800138000',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonPath('message', '登录成功')
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'phone'],
                    'token',
                ],
            ]);
    }

    public function test_login_fails_with_invalid_password(): void
    {
        User::factory()->create([
            'phone' => '13800138000',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'phone' => '13800138000',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone']);
    }

    public function test_authenticated_user_can_fetch_profile(): void
    {
        $user = User::factory()->create([
            'phone' => '13800138000',
            'password' => Hash::make('password123'),
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/auth/me');

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonPath('data.id', $user->id);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create([
            'phone' => '13800138000',
            'password' => Hash::make('password123'),
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonPath('message', '退出成功');

        $this->assertDatabaseMissing('personal_access_tokens', [
            'token' => hash('sha256', explode('|', $token)[1] ?? $token),
        ]);
    }
}
