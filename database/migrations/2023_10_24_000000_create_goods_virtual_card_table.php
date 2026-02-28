<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('goods_virtual_card', function (Blueprint $table) {
            $table->increments('card_id');
            $table->unsignedInteger('goods_id')->default(0)->index()->comment('商品ID');
            $table->string('card_sn')->default('')->index()->comment('卡号');
            $table->string('card_password')->default('')->comment('卡密');
            $table->integer('add_date')->default(0)->comment('添加日期');
            $table->integer('end_date')->default(0)->comment('结束日期');
            $table->boolean('is_saled')->default(false)->index()->comment('是否已售');
            $table->string('order_sn')->default('')->comment('订单号');
            $table->string('crc32')->default('0')->comment('CRC32校验');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `goods_virtual_card` COMMENT '虚拟商品卡密表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_virtual_card');
    }
};
