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
        Schema::create('activity_group', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->default(0)->comment('父级ID');
            $table->unsignedInteger('goods_id')->default(0)->comment('商品ID');
            $table->decimal('goods_price')->unsigned()->default(0)->comment('商品价格');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');

            $table->unique(['parent_id', 'goods_id'], 'parent_id_goods_id');
        });

        DB::statement("ALTER TABLE `activity_group` COMMENT '团购活动表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_group');
    }
};
