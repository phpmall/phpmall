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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级的ID');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->string('user_name')->default('')->comment('用户昵称');
            $table->unsignedBigInteger('content_id')->comment('内容ID');
            $table->string('comment')->default('')->comment('评论内容');
            $table->unsignedInteger('rank')->default(0)->comment('评论等级');
            $table->string('user_agent')->default('')->comment('User Agent');
            $table->string('ip_address')->default('')->comment('IP地址');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态:1正常,2禁用');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('内容评论表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
