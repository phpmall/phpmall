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
        Schema::create('user_notifications', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedBigInteger('notification_id')->comment('通知ID');
            $table->timestamp('read_at')->nullable()->comment('阅读时间');
            $table->timestamps();

            $table->unique(['user_id', 'notification_id'], 'udx_user_notifications_user_notification');
            $table->index('notification_id', 'idx_user_notifications_notification_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};
