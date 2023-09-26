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
            $table->string('type')->comment('凭证类型:mobile,email,wechat');
            $table->string('identifier')->unique()->comment('唯一标识:如手机号码,电子邮箱,openid');
            $table->string('credential')->comment('凭证:密码或token');
            $table->dateTime('verified_time')->nullable()->comment('验证时间');
            $table->unsignedInteger('status')->default(1)->comment('状态:1正常,2禁用');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('用户认证表');
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
