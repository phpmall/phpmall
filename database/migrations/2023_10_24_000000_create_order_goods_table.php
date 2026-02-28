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
        Schema::create('order_goods', function (Blueprint $table) {
            $table->increments('rec_id');
            $table->unsignedInteger('order_id')->default(0)->index()->comment('订单ID');
            $table->unsignedInteger('goods_id')->default(0)->index()->comment('商品ID');
            $table->string('goods_name')->default('')->comment('商品名称');
            $table->string('goods_sn')->default('')->comment('商品编号');
            $table->unsignedInteger('product_id')->default(0)->comment('货品ID');
            $table->unsignedInteger('goods_number')->default(1)->comment('商品数量');
            $table->decimal('market_price')->default(0)->comment('市场价格');
            $table->decimal('goods_price')->default(0)->comment('商品价格');
            $table->text('goods_attr')->nullable()->comment('商品属性');
            $table->unsignedInteger('send_number')->default(0)->comment('发货数量');
            $table->unsignedTinyInteger('is_real')->default(0)->comment('是否实物');
            $table->string('extension_code')->default('')->comment('扩展代码');
            $table->unsignedInteger('parent_id')->default(0)->comment('父级ID');
            $table->unsignedInteger('is_gift')->default(0)->comment('是否赠品');
            $table->string('goods_attr_id')->default('')->comment('商品属性ID');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `order_goods` COMMENT '订单商品表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_goods');
    }
};
