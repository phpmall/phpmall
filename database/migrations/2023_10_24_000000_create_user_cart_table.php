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
        Schema::create('user_cart', function (Blueprint $table) {
            $table->increments('rec_id');
            $table->unsignedInteger('user_id')->default(0)->index()->comment('用户ID');
            $table->string('session_id')->default('')->index()->comment('SessionID');
            $table->unsignedInteger('goods_id')->default(0)->index()->comment('商品ID');
            $table->string('goods_sn')->default('')->comment('商品编号');
            $table->unsignedInteger('product_id')->default(0)->comment('货品ID');
            $table->string('goods_name')->default('')->comment('商品名称');
            $table->decimal('market_price')->unsigned()->default(0)->comment('市场价格');
            $table->decimal('goods_price')->default(0)->comment('商品价格');
            $table->unsignedInteger('goods_number')->default(0)->comment('商品数量');
            $table->text('goods_attr')->nullable()->comment('商品属性');
            $table->unsignedTinyInteger('is_real')->default(0)->comment('是否实物');
            $table->string('extension_code')->default('')->comment('扩展代码');
            $table->unsignedInteger('parent_id')->default(0)->comment('父级ID');
            $table->unsignedTinyInteger('rec_type')->default(0)->comment('记录类型');
            $table->unsignedInteger('is_gift')->default(0)->comment('是否赠品');
            $table->unsignedTinyInteger('is_shipping')->default(0)->comment('是否包邮');
            $table->unsignedTinyInteger('can_handsel')->default(0)->comment('是否可以赠送');
            $table->string('goods_attr_id')->default('')->comment('商品属性ID');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `user_cart` COMMENT '购物车表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_cart');
    }
};
