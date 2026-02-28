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
        Schema::create('activity', function (Blueprint $table) {
            $table->increments('act_id');
            $table->string('act_name')->index()->comment('活动名称');
            $table->unsignedInteger('start_time')->comment('开始时间');
            $table->unsignedInteger('end_time')->comment('结束时间');
            $table->string('user_rank')->comment('用户等级');
            $table->unsignedTinyInteger('act_range')->comment('活动范围');
            $table->string('act_range_ext')->comment('活动范围扩展');
            $table->decimal('min_amount')->unsigned()->comment('最小金额');
            $table->decimal('max_amount')->unsigned()->comment('最大金额');
            $table->unsignedTinyInteger('act_type')->comment('活动类型');
            $table->decimal('act_type_ext')->unsigned()->comment('活动类型扩展');
            $table->text('gift')->nullable()->comment('赠品');
            $table->unsignedTinyInteger('sort_order')->default(50)->comment('排序顺序');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `activity` COMMENT '促销活动表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity');
    }
};
