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
        Schema::create('crm_customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('昵称');
            $table->string('avatar')->comment('头像');
            $table->date('birthday')->comment('生日');
            $table->string('mobile')->unique()->comment('登录手机号');
            $table->timestamp('mobile_verified_at')->nullable()->comment('手机号验证时间');
            $table->string('password')->comment('登录用户密码');
            $table->rememberToken()->comment('会话令牌');
            $table->unsignedTinyInteger('status')->comment('状态:1正常;2禁用');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('客户表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_customers');
    }
};
