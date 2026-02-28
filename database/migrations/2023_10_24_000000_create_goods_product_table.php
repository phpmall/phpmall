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
        Schema::create('goods_product', function (Blueprint $table) {
            $table->increments('product_id');
            $table->unsignedInteger('goods_id')->default(0)->comment('商品ID');
            $table->string('goods_attr')->nullable()->comment('商品属性');
            $table->string('product_sn')->nullable()->comment('货号');
            $table->unsignedInteger('product_number')->nullable()->default(0)->comment('库存数量');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `goods_product` COMMENT '商品货品表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_product');
    }
};
