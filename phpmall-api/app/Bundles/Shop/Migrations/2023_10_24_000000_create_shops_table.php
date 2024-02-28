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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('merchant_id')->comment('商户id');
            $table->string('shop_name')->comment('店铺名称');
            $table->string('owner_name')->comment('店主姓名');
            $table->string('owner_phone')->comment('店主电话');
            $table->string('owner_email')->comment('店主邮箱');
            $table->string('store_address')->comment('店铺地址');
            $table->string('store_status')->comment('店铺状态：如"正常营业"、"关店维修"');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('店铺表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
