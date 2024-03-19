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
        Schema::create('user_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable(false)->comment('用户ID');
            $table->string('event_type')->default('')->nullable(false)->comment('事件类型，用于区分不同的用户操作或系统事件');
            $table->dateTime('event_time')->nullable(false)->comment('事件发生的时间');
            $table->text('event_details')->comment('事件的详细信息，推荐json格式');
            $table->string('ip_address')->default('')->nullable(false)->comment('用户的IP地址');
            $table->string('user_agent')->default('')->nullable(false)->comment('用户代理字符串');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('用户日志表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_logs');
    }
};
