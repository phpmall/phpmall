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
        Schema::create('activity_snatch', function (Blueprint $table) {
            $table->increments('log_id');
            $table->unsignedTinyInteger('snatch_id')->default(0)->index()->comment('夺宝ID');
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->decimal('bid_price')->default(0)->comment('出价');
            $table->unsignedInteger('bid_time')->default(0)->comment('出价时间');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `activity_snatch` COMMENT '夺宝奇兵活动表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_snatch');
    }
};
