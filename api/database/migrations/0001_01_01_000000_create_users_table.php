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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 36)->unique('uuid_unique')->nullable(false)->comment('全局ID');
            $table->string('name')->default('')->nullable(false)->comment('昵称');
            $table->string('avatar')->default('')->nullable(false)->comment('头像');
            $table->string('password')->default('')->nullable(false)->comment('登录密码');
            $table->unsignedTinyInteger('status')->nullable(false)->comment('状态:1正常;2禁用');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->comment('用户表');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('uuid')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
            $table->comment('密码重置表');
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
            $table->comment('会话表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
