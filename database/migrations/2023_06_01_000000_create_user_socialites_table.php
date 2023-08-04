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
        Schema::create('user_socialites', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->string('type')->comment('凭证类型:email,wechat');
            $table->string('identifier')->comment('唯一标识:如电子邮箱,openid');
            $table->string('credentials')->comment('凭证:密码,token');
            $table->rememberToken()->comment('会话Token');
            $table->string('reset_token')->comment('重置Token');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['identifier', 'deleted_at']);
            $table->comment('用户社会化登录表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_socialites');
    }
};
