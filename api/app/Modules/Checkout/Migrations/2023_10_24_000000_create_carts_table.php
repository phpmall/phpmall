<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('merchant_id')->comment('商户id');
            $table->unsignedBigInteger('shop_id')->comment('店铺ID');
            $table->unsignedBigInteger('user_id')->comment('买家ID');
            $table->unsignedBigInteger('product_id')->comment('产品ID');
            $table->unsignedBigInteger('quantity')->comment('商品数量');
            $table->timestamps();
            $table->comment('购物车表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
