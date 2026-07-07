<?php

declare(strict_types=1);

namespace Tests\Feature\Api\User;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class JwtAuthTest extends TestCase
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

    public function test_login_returns_jwt_tokens(): void
    {
        $this->createUser();

        $response = $this->postJson('/api/user/auth/login', [
            'mobile' => '13800138000',
            'password' => 'password123',
            'captcha' => 'test',
            'uuid' => 'test-uuid',
            'device_name' => 'test-device',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'data' => [
                    'access_token',
                    'refresh_token',
                    'expires_in',
                    'token_type',
                ],
            ])
            ->assertJson(['code' => 0]);

        $this->assertNotEmpty($response->json('data.access_token'));
        $this->assertNotEmpty($response->json('data.refresh_token'));
        $this->assertEquals('Bearer', $response->json('data.token_type'));
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $this->createUser();

        $response = $this->postJson('/api/user/auth/login', [
            'mobile' => '13800138000',
            'password' => 'wrongpassword',
            'captcha' => 'test',
            'uuid' => 'test-uuid',
            'device_name' => 'test-device',
        ]);

        $response->assertStatus(422);
    }

    public function test_signup_returns_jwt_tokens(): void
    {
        $response = $this->postJson('/api/user/auth/signup', [
            'mobile' => '13800138001',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'device_name' => 'test-device',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'data' => [
                    'access_token',
                    'refresh_token',
                    'expires_in',
                    'token_type',
                ],
            ])
            ->assertJson(['code' => 0]);
    }

    public function test_protected_route_requires_jwt(): void
    {
        $response = $this->getJson('/api/user/profile');

        $response->assertStatus(401);
    }

    public function test_protected_route_accepts_valid_jwt(): void
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

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user/profile');

        $response->assertStatus(200);
    }

    public function test_logout_blacklists_token(): void
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

        $logoutResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/auth/logout');

        $logoutResponse->assertStatus(200)
            ->assertJson(['code' => 0]);

        // 再次使用同一 token 访问受保护路由应被拒绝
        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user/profile');

        $response->assertStatus(401);
    }
}
