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
        Schema::create('category_product_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->comment('商品分类id');
            $table->unsignedBigInteger('product_attribute_id')->comment('商品属性id');
            $table->timestamps();
            $table->comment('分类与商品属性关联表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_product_attributes');
    }
};
