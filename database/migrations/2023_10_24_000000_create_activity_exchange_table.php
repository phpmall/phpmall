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
        Schema::create('activity_exchange', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('goods_id')->default(0)->index()->comment('商品ID');
            $table->unsignedInteger('exchange_integral')->default(0)->comment('兑换积分');
            $table->unsignedTinyInteger('is_exchange')->default(0)->comment('是否可兑换');
            $table->unsignedTinyInteger('is_hot')->default(0)->comment('是否热门');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `activity_exchange` COMMENT '积分兑换活动表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_exchange');
    }
};
