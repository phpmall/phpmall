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
        Schema::create('goods_link_goods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('goods_id')->default(0)->comment('商品ID');
            $table->unsignedInteger('link_goods_id')->default(0)->comment('关联商品ID');
            $table->unsignedTinyInteger('is_double')->default(0)->comment('是否双向关联');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');

            $table->unique(['goods_id', 'link_goods_id'], 'goods_id_link_goods_id');
        });

        DB::statement("ALTER TABLE `goods_link_goods` COMMENT '关联商品表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_link_goods');
    }
};
