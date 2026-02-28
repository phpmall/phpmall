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
        Schema::create('goods_attr', function (Blueprint $table) {
            $table->increments('goods_attr_id');
            $table->unsignedInteger('goods_id')->default(0)->index()->comment('商品ID');
            $table->unsignedInteger('attr_id')->default(0)->index()->comment('属性ID');
            $table->text('attr_value')->nullable()->comment('属性值');
            $table->string('attr_price')->default('')->comment('属性价格');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `goods_attr` COMMENT '商品属性表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_attr');
    }
};
