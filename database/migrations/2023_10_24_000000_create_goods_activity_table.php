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
        Schema::create('goods_activity', function (Blueprint $table) {
            $table->increments('act_id');
            $table->string('act_name')->comment('活动名称');
            $table->text('act_desc')->nullable()->comment('活动描述');
            $table->unsignedTinyInteger('act_type')->comment('活动类型');
            $table->unsignedInteger('goods_id')->comment('商品ID');
            $table->unsignedInteger('product_id')->default(0)->comment('货品ID');
            $table->string('goods_name')->comment('商品名称');
            $table->unsignedInteger('start_time')->comment('开始时间');
            $table->unsignedInteger('end_time')->comment('结束时间');
            $table->unsignedTinyInteger('is_finished')->comment('是否结束');
            $table->text('ext_info')->nullable()->comment('扩展信息');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');

            $table->index(['act_name', 'act_type', 'goods_id'], 'act_name');
        });

        DB::statement("ALTER TABLE `goods_activity` COMMENT '商品活动关联表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_activity');
    }
};
