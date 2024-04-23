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
        Schema::create('product_ladders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->comment('商品id');
            $table->unsignedInteger('count')->comment('满足的商品数量');
            $table->decimal('discount')->comment('折扣');
            $table->decimal('price')->comment('折后价格');
            $table->comment('商品阶梯价格表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_ladders');
    }
};
