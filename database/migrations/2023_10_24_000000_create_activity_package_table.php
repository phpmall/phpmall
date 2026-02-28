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
        Schema::create('activity_package', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('package_id')->default(0)->comment('组合ID');
            $table->unsignedInteger('goods_id')->default(0)->comment('商品ID');
            $table->unsignedInteger('product_id')->default(0)->comment('货品ID');
            $table->unsignedInteger('goods_number')->default(1)->comment('商品数量');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');

            $table->unique(['package_id', 'goods_id', 'product_id'], 'package_id_goods_id_product_id');
        });

        DB::statement("ALTER TABLE `activity_package` COMMENT '超值礼包活动表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_package');
    }
};
