<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // messages (站内消息)
        Schema::create('messages', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->comment('用户ID，NULL=广播消息');
            $table->unsignedTinyInteger('type')->default(1)->comment('1=系统通知 2=订单通知 3=营销消息 4=活动消息');
            $table->string('title', 200)->comment('消息标题');
            $table->text('content')->comment('消息内容');
            $table->unsignedTinyInteger('is_read')->default(0)->comment('0=未读 1=已读');
            $table->timestamp('read_at')->nullable()->comment('阅读时间');
            $table->string('link_url', 500)->nullable()->comment('跳转链接');
            $table->json('extra_data')->nullable()->comment('扩展数据');
            $table->unsignedTinyInteger('status')->default(1)->comment('0=隐藏 1=显示');
            $table->timestamps();

            $table->index('user_id', 'idx_messages_user_id');
            $table->index(['user_id', 'is_read'], 'idx_messages_user_id_is_read');
            $table->index('type', 'idx_messages_type');
            $table->index('created_at', 'idx_messages_created_at');
        });

        // notifications (系统通知/公告)
        Schema::create('notifications', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('sender_id')->nullable()->comment('发送者ID，NULL=系统发送');
            $table->string('sender_type', 50)->nullable()->comment('发送者类型');
            $table->unsignedTinyInteger('type')->default(1)->comment('1=平台公告 2=商家通知 3=用户通知');
            $table->string('title', 200)->comment('通知标题');
            $table->text('content')->comment('通知内容');
            $table->unsignedTinyInteger('priority')->default(1)->comment('1=普通 2=重要 3=紧急');
            $table->timestamp('publish_at')->nullable()->comment('发布时间');
            $table->timestamp('expire_at')->nullable()->comment('过期时间');
            $table->unsignedTinyInteger('status')->default(1)->comment('0=草稿 1=已发布 2=已撤回');
            $table->unsignedInteger('view_count')->default(0)->comment('浏览次数');
            $table->timestamps();
            $table->softDeletes();

            $table->index('type', 'idx_notifications_type');
            $table->index('status', 'idx_notifications_status');
            $table->index('publish_at', 'idx_notifications_publish_at');
            $table->index('priority', 'idx_notifications_priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('messages');
    }
};
