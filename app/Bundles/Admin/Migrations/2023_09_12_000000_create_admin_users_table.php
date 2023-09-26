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
        Schema::create('admin_users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->comment('登录用户名');
            $table->string('password')->comment('登录用户密码');
            $table->string('password_salt')->comment('用户密码盐值');
            $table->string('name')->comment('昵称');
            $table->string('avatar')->comment('头像');
            $table->string('email')->comment('电子邮箱');
            $table->timestamp('email_verified_at')->nullable()->comment('电子邮箱验证时间');
            $table->string('mobile')->unique()->comment('手机号码');
            $table->timestamp('mobile_verified_at')->nullable()->comment('手机号码验证时间');
            $table->rememberToken();
            $table->string('status')->default(1)->comment('状态:1正常,2禁用');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('管理员表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_users');
    }
};
