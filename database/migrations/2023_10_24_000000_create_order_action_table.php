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
        Schema::create('order_action', function (Blueprint $table) {
            $table->increments('action_id');
            $table->unsignedInteger('order_id')->default(0)->index()->comment('订单ID');
            $table->string('action_user')->default('')->comment('操作用户');
            $table->unsignedTinyInteger('order_status')->default(0)->comment('订单状态');
            $table->unsignedTinyInteger('shipping_status')->default(0)->comment('配送状态');
            $table->unsignedTinyInteger('pay_status')->default(0)->comment('支付状态');
            $table->unsignedTinyInteger('action_place')->default(0)->comment('操作位置');
            $table->string('action_note')->default('')->comment('操作备注');
            $table->unsignedInteger('log_time')->default(0)->comment('日志时间');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `order_action` COMMENT '订单操作记录表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_action');
    }
};
