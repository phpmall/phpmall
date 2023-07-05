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
        Schema::create('seller_address', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('seller_id')->comment('卖家ID');
            $table->string('name')->comment('地址名称');
            $table->string('consignee')->comment('收件人姓名');
            $table->string('mobile')->comment('收件人电话');
            $table->string('country_name')->default('中国')->comment('国家');
            $table->string('country_code')->default('CHN')->comment('国家编码');
            $table->string('province_name')->comment('省份');
            $table->string('province_code')->comment('省份编码');
            $table->string('city_name')->comment('城市');
            $table->string('city_code')->comment('城市编码');
            $table->string('district_name')->comment('区/县');
            $table->string('district_code')->comment('区/县编码');
            $table->string('detail_address')->comment('详情地址');
            $table->unsignedTinyInteger('send_status')->default(0)->comment('默认发货地址：0->否；1->是');
            $table->unsignedTinyInteger('receive_status')->default(0)->comment('是否默认收货地址：0->否；1->是');
            $table->unsignedTinyInteger('invoice_status')->default(0)->comment('默认收票地址：0->否；1->是');
            $table->string('latitude')->comment('纬度');
            $table->string('longitude')->comment('经度');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('卖家地址表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_address');
    }
};
