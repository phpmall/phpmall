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
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('merchant_id')->index()->comment('商户id');
            $table->unsignedBigInteger('shop_id')->index()->comment('店铺id');
            $table->unsignedBigInteger('store_id')->index()->comment('门店ID');
            $table->unsignedBigInteger('user_id')->index()->comment('用户ID');
            $table->string('status')->default(1)->comment('状态:1正常,2禁用');
            $table->timestamps();
            $table->comment('商户管理员表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};
