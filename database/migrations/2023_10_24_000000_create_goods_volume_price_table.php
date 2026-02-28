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
        Schema::create('goods_volume_price', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('price_type')->comment('价格类型');
            $table->unsignedInteger('goods_id')->comment('商品ID');
            $table->unsignedInteger('volume_number')->default(0)->comment('数量');
            $table->decimal('volume_price')->default(0)->comment('批发价格');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');

            $table->unique(['price_type', 'goods_id', 'volume_number'], 'price_type_goods_id_volume_number');
        });

        DB::statement("ALTER TABLE `goods_volume_price` COMMENT '商品批量价格表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_volume_price');
    }
};
