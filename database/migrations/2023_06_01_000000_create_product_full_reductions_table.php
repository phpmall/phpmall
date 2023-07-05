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
        Schema::create('product_full_reductions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->comment('商品id');
            $table->decimal('full_price', 2)->comment('商品满足金额');
            $table->decimal('reduce_price', 2)->comment('商品减少金额');
            $table->comment('商品满减价格表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_full_reductions');
    }
};
