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
        Schema::create('coupon_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('merchant_id')->comment('商户id');
            $table->unsignedBigInteger('shop_id')->comment('店铺id');

            $table->timestamps();
            $table->comment('优惠券与商品分类关联表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_categories');
    }
};
