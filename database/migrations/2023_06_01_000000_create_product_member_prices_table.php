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
        Schema::create('product_member_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->comment('商品id');
            $table->unsignedBigInteger('member_level_id')->comment('会员等级id');
            $table->string('member_level_name')->comment('会员等级名称');
            $table->decimal('member_discount')->comment('会员折扣');
            $table->decimal('member_price')->comment('会员价格');
            $table->comment('商品会员价格表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_member_prices');
    }
};
