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
        Schema::create('user_tag', function (Blueprint $table) {
            $table->increments('tag_id');
            $table->unsignedInteger('user_id')->default(0)->index()->comment('用户ID');
            $table->unsignedInteger('goods_id')->default(0)->index()->comment('商品ID');
            $table->string('tag_words')->default('')->comment('标签词');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `user_tag` COMMENT '用户标签表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_tag');
    }
};
