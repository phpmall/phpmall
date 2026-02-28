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
        Schema::create('goods_cat', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('goods_id')->default(0)->comment('商品ID');
            $table->unsignedInteger('cat_id')->default(0)->comment('分类ID');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');

            $table->unique(['goods_id', 'cat_id'], 'goods_id_cat_id');
        });

        DB::statement("ALTER TABLE `goods_cat` COMMENT '商品分类关联表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_cat');
    }
};
