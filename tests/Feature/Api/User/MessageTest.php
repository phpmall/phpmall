<?php

declare(strict_types=1);

namespace Tests\Feature\Api\User;

use App\Modules\Message\Models\Message;
use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\Traits\JwtTokenHelper;

class MessageTest extends TestCase
{
    use JwtTokenHelper;
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

        $this->token = $this->generateJwtToken($this->user);
    }

    public function test_user_can_list_messages(): void
    {
        Message::create([
            'user_id' => $this->user->id,
            'type' => 1,
            'title' => '系统消息',
            'content' => '欢迎',
            'is_read' => 0,
            'status' => 1,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/user/messages');

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.title', '系统消息')
            ->assertJsonPath('data.items.0.type', 'system');
    }

    public function test_user_can_filter_messages_by_read_status(): void
    {
        Message::create([
            'user_id' => $this->user->id,
            'type' => 1,
            'title' => '已读消息',
            'content' => '已读',
            'is_read' => 1,
            'status' => 1,
        ]);
        Message::create([
            'user_id' => $this->user->id,
            'type' => 2,
            'title' => '未读消息',
            'content' => '未读',
            'is_read' => 0,
            'status' => 1,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/user/messages?is_read=0');

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.title', '未读消息');
    }

    public function test_user_cannot_see_other_users_messages(): void
    {
        $otherUser = User::factory()->create([
            'phone' => '13900139000',
            'password' => Hash::make('password123'),
        ]);

        Message::create([
            'user_id' => $otherUser->id,
            'type' => 1,
            'title' => '他人消息',
            'content' => '秘密',
            'is_read' => 0,
            'status' => 1,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/user/messages');

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonCount(0, 'data.items');
    }

    public function test_user_can_get_unread_count(): void
    {
        Message::create([
            'user_id' => $this->user->id,
            'type' => 1,
            'title' => '系统未读',
            'content' => '系统',
            'is_read' => 0,
            'status' => 1,
        ]);
        Message::create([
            'user_id' => $this->user->id,
            'type' => 2,
            'title' => '订单未读',
            'content' => '订单',
            'is_read' => 0,
            'status' => 1,
        ]);
        Message::create([
            'user_id' => $this->user->id,
            'type' => 1,
            'title' => '系统已读',
            'content' => '系统',
            'is_read' => 1,
            'status' => 1,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/user/messages/unread-count');

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonPath('data.total', 2)
            ->assertJsonPath('data.system', 1)
            ->assertJsonPath('data.order', 1);
    }

    public function test_user_can_mark_message_as_read(): void
    {
        $message = Message::create([
            'user_id' => $this->user->id,
            'type' => 1,
            'title' => '待读',
            'content' => '待读',
            'is_read' => 0,
            'status' => 1,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson("/api/user/messages/{$message->id}/read");

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonPath('data.is_read', 1);

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'is_read' => 1,
        ]);
    }

    public function test_user_cannot_mark_other_users_message_as_read(): void
    {
        $otherUser = User::factory()->create([
            'phone' => '13900139000',
            'password' => Hash::make('password123'),
        ]);

        $message = Message::create([
            'user_id' => $otherUser->id,
            'type' => 1,
            'title' => '他人消息',
            'content' => '秘密',
            'is_read' => 0,
            'status' => 1,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson("/api/user/messages/{$message->id}/read");

        $response->assertStatus(200)
            ->assertJsonPath('code', 50001)
            ->assertJsonPath('message', '消息不存在');
    }
}
