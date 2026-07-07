<?php

declare(strict_types=1);

namespace Tests\Feature\Api\User;

use App\Modules\Notification\Models\Notification;
use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\Traits\JwtTokenHelper;

class NotificationTest extends TestCase
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

    public function test_user_can_list_notifications(): void
    {
        Notification::create([
            'type' => 1,
            'title' => '平台公告',
            'content' => '公告内容',
            'status' => 1,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/user/notifications');

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.title', '平台公告')
            ->assertJsonPath('data.items.0.type', 'system')
            ->assertJsonPath('data.items.0.is_read', 0);
    }

    public function test_user_can_filter_notifications_by_read_status(): void
    {
        $notification = Notification::create([
            'type' => 1,
            'title' => '已读公告',
            'content' => '已读',
            'status' => 1,
        ]);
        Notification::create([
            'type' => 1,
            'title' => '未读公告',
            'content' => '未读',
            'status' => 1,
        ]);

        DB::table('user_notifications')->insert([
            'user_id' => $this->user->id,
            'notification_id' => $notification->id,
            'read_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/user/notifications?is_read=0');

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.title', '未读公告');
    }

    public function test_expired_notifications_are_not_listed(): void
    {
        Notification::create([
            'type' => 1,
            'title' => '过期公告',
            'content' => '过期',
            'status' => 1,
            'expire_at' => now()->subDay(),
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/user/notifications');

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonCount(0, 'data.items');
    }

    public function test_user_can_show_notification(): void
    {
        $notification = Notification::create([
            'type' => 1,
            'title' => '详情公告',
            'content' => '详情',
            'status' => 1,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson("/api/user/notifications/{$notification->id}");

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonPath('data.title', '详情公告')
            ->assertJsonPath('data.type', 'system');
    }

    public function test_user_can_mark_notification_as_read(): void
    {
        $notification = Notification::create([
            'type' => 1,
            'title' => '待读公告',
            'content' => '待读',
            'status' => 1,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson("/api/user/notifications/{$notification->id}/read");

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonPath('data.message', '标记成功');

        $this->assertDatabaseHas('user_notifications', [
            'user_id' => $this->user->id,
            'notification_id' => $notification->id,
        ]);
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        Notification::create([
            'type' => 1,
            'title' => '公告一',
            'content' => '一',
            'status' => 1,
        ]);
        Notification::create([
            'type' => 1,
            'title' => '公告二',
            'content' => '二',
            'status' => 1,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson('/api/user/notifications/read-all');

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonPath('data.message', '全部已读');

        $this->assertDatabaseCount('user_notifications', 2);
    }

    public function test_user_can_destroy_notification_record(): void
    {
        $notification = Notification::create([
            'type' => 1,
            'title' => '待删公告',
            'content' => '待删',
            'status' => 1,
        ]);

        DB::table('user_notifications')->insert([
            'user_id' => $this->user->id,
            'notification_id' => $notification->id,
            'read_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->deleteJson("/api/user/notifications/{$notification->id}");

        $response->assertStatus(200)
            ->assertJsonPath('code', 0)
            ->assertJsonPath('data.message', '删除成功');

        $this->assertDatabaseMissing('user_notifications', [
            'user_id' => $this->user->id,
            'notification_id' => $notification->id,
        ]);
    }
}
