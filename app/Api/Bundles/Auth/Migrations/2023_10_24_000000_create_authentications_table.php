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
        Schema::create('authentications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->string('type')->comment('凭证类型');
            $table->string('identifier')->comment('标识');
            $table->string('credentials')->comment('凭证或token');
            $table->dateTime('verified_time')->comment('验证时间');
            $table->unsignedInteger('status')->comment('状态:1正常,2禁用');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['type', 'identifier']);
            $table->comment('用户认证表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authentications');
    }
};
