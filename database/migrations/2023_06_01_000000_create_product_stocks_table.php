<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->comment('商品id');
            $table->string('sku_code')->nullable(false)->comment('sku编码');
            $table->decimal('price', 2)->comment('价格');
            $table->decimal('promotion_price', 2)->comment('单品促销价格');
            $table->unsignedInteger('stock')->default(0)->comment('库存');
            $table->unsignedInteger('low_stock')->comment('预警库存');
            $table->string('sp1')->comment('规格属性1');
            $table->string('sp2')->comment('规格属性2');
            $table->string('sp3')->comment('规格属性3');
            $table->string('pic')->comment('展示图片');
            $table->unsignedInteger('sale')->comment('销量');
            $table->unsignedInteger('lock_stock')->default(0)->comment('锁定库存');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('货品库存表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stocks');
    }
};
