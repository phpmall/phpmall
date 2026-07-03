<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Common;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Tests\Traits\JwtTokenHelper;

class AuthRefreshTest extends TestCase
{
    use JwtTokenHelper;
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

    public function test_refresh_returns_new_tokens(): void
    {
        $user = $this->createUser();
        $token = $this->generateJwtToken($user);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/common/v1/auth/refresh');

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

        // 旧 token 应已被拉黑
        $profileResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user/profile');

        $profileResponse->assertStatus(401);
    }

    public function test_refresh_fails_with_blacklisted_token(): void
    {
        $user = $this->createUser();
        $token = $this->generateJwtToken($user);

        // 首次刷新
        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/common/v1/auth/refresh');

        // 再次使用同一 token 刷新应失败
        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/common/v1/auth/refresh');

        $response->assertStatus(401)
            ->assertJson([
                'code' => 401,
                'message' => 'Token revoked',
            ]);
    }

    public function test_refresh_fails_without_token(): void
    {
        $response = $this->postJson('/api/common/v1/auth/refresh');

        $response->assertStatus(401)
            ->assertJson([
                'code' => 401,
                'message' => 'Missing token',
            ]);
    }
}
