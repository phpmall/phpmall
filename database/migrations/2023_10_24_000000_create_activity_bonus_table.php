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
        Schema::create('activity_bonus', function (Blueprint $table) {
            $table->increments('type_id');
            $table->string('type_name')->default('')->comment('红包类型名称');
            $table->decimal('type_money')->default(0)->comment('红包金额');
            $table->unsignedTinyInteger('send_type')->default(0)->comment('发放类型');
            $table->decimal('min_amount')->unsigned()->default(0)->comment('最小金额');
            $table->decimal('max_amount')->unsigned()->default(0)->comment('最大金额');
            $table->integer('send_start_date')->default(0)->comment('发放开始时间');
            $table->integer('send_end_date')->default(0)->comment('发放结束时间');
            $table->integer('use_start_date')->default(0)->comment('使用开始时间');
            $table->integer('use_end_date')->default(0)->comment('使用结束时间');
            $table->decimal('min_goods_amount')->unsigned()->default(0)->comment('最小商品金额');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `activity_bonus` COMMENT '红包活动表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_bonus');
    }
};
