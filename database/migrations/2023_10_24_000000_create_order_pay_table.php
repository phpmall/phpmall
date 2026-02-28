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
        Schema::create('order_pay', function (Blueprint $table) {
            $table->increments('log_id');
            $table->unsignedInteger('order_id')->default(0)->comment('订单ID');
            $table->decimal('order_amount')->unsigned()->comment('订单金额');
            $table->unsignedTinyInteger('order_type')->default(0)->comment('订单类型');
            $table->unsignedTinyInteger('is_paid')->default(0)->comment('是否已支付');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `order_pay` COMMENT '订单支付记录表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_pay');
    }
};
