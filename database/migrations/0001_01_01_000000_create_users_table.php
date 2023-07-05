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
            $table->string('name')->comment('昵称');
            $table->string('avatar')->comment('头像');
            $table->date('birthday')->comment('生日');
            $table->string('mobile')->comment('手机号码');
            $table->timestamp('mobile_verified_at')->nullable()->comment('手机号码验证时间');
            $table->string('password')->comment('登录密码');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态:1正常;2禁用');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['mobile', 'deleted_at']);
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('mobile')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
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
