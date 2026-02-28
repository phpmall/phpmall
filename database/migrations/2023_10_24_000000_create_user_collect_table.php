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
        Schema::create('user_collect', function (Blueprint $table) {
            $table->increments('rec_id');
            $table->unsignedInteger('user_id')->default(0)->index()->comment('用户ID');
            $table->unsignedInteger('goods_id')->default(0)->index()->comment('商品ID');
            $table->unsignedInteger('add_time')->default(0)->comment('添加时间');
            $table->boolean('is_attention')->default(false)->index()->comment('是否关注');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `user_collect` COMMENT '用户收藏表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_collect');
    }
};
