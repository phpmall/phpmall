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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('seller_id')->comment('卖家ID');
            $table->string('store_logo')->comment('店铺LOGO');
            $table->string('store_introduce')->comment('店铺简介');
            $table->string('store_background')->comment('店铺背景图');
            $table->string('store_category')->comment('店铺所属类别');
            $table->string('store_rating')->comment('店铺评分：一般取值范围在0~5之间');
            $table->string('store_status')->comment('店铺状态：如"正常营业"、"关店维修"');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
