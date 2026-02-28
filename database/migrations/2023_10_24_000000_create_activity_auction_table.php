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
        Schema::create('activity_auction', function (Blueprint $table) {
            $table->increments('log_id');
            $table->unsignedInteger('act_id')->index()->comment('活动ID');
            $table->unsignedInteger('bid_user')->comment('竞价用户');
            $table->decimal('bid_price')->unsigned()->comment('竞价金额');
            $table->unsignedInteger('bid_time')->comment('竞价时间');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `activity_auction` COMMENT '拍卖活动表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_auction');
    }
};
