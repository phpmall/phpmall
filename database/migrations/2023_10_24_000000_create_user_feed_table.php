<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_feed', function (Blueprint $table) {
            $table->increments('feed_id');
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->unsignedInteger('value_id')->default(0)->comment('值ID');
            $table->unsignedInteger('goods_id')->default(0)->comment('商品ID');
            $table->unsignedTinyInteger('feed_type')->default(0)->comment('动态类型');
            $table->unsignedTinyInteger('is_feed')->default(0)->comment('是否动态');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `user_feed` COMMENT '用户反馈表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_feed');
    }
};
