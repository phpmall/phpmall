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
        Schema::create('order_delivery_goods', function (Blueprint $table) {
            $table->increments('rec_id');
            $table->unsignedInteger('delivery_id')->default(0)->comment('发货单ID');
            $table->unsignedInteger('goods_id')->default(0)->index()->comment('商品ID');
            $table->unsignedInteger('product_id')->nullable()->default(0)->comment('货品ID');
            $table->string('product_sn')->nullable()->comment('货号');
            $table->string('goods_name')->nullable()->comment('商品名称');
            $table->string('brand_name')->nullable()->comment('品牌名称');
            $table->string('goods_sn')->nullable()->comment('商品货号');
            $table->unsignedTinyInteger('is_real')->nullable()->default(0)->comment('是否实物');
            $table->string('extension_code')->nullable()->comment('扩展代码');
            $table->unsignedInteger('parent_id')->nullable()->default(0)->comment('父级ID');
            $table->unsignedInteger('send_number')->nullable()->default(0)->comment('发货数量');
            $table->text('goods_attr')->nullable()->comment('商品属性');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');

            $table->index(['delivery_id', 'goods_id'], 'delivery_id');
        });

        DB::statement("ALTER TABLE `order_delivery_goods` COMMENT '发货商品表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_delivery_goods');
    }
};
