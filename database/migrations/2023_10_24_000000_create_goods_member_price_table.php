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
        Schema::create('goods_member_price', function (Blueprint $table) {
            $table->increments('price_id');
            $table->unsignedInteger('goods_id')->default(0)->comment('商品ID');
            $table->tinyInteger('user_rank')->default(0)->comment('用户等级');
            $table->decimal('user_price')->default(0)->comment('会员价格');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');

            $table->index(['goods_id', 'user_rank'], 'goods_id');
        });

        DB::statement("ALTER TABLE `goods_member_price` COMMENT '商品会员价格表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_member_price');
    }
};
