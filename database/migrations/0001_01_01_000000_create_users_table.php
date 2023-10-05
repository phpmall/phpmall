<?php

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
            $table->string('username')->unique()->comment('登录用户名');
            $table->string('password')->comment('登录用户密码');
            $table->string('password_salt')->comment('用户密码盐值');
            $table->string('name')->comment('昵称');
            $table->string('avatar')->comment('头像');
            $table->date('birthday')->comment('生日');
            $table->unsignedTinyInteger('status')->comment('状态:1正常;2禁用');
            $table->rememberToken()->comment('会话令牌');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('用户表');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('username')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
            $table->comment('用户密码重置表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
    }
};
