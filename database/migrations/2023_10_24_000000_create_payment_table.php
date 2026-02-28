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
        Schema::create('payment', function (Blueprint $table) {
            $table->tinyIncrements('pay_id');
            $table->string('pay_code')->default('')->unique()->comment('支付方式编码');
            $table->string('pay_name')->default('')->comment('支付名称');
            $table->string('pay_fee')->default('0')->comment('支付手续费');
            $table->text('pay_desc')->nullable()->comment('支付描述');
            $table->unsignedTinyInteger('pay_order')->default(0)->comment('排序');
            $table->text('pay_config')->nullable()->comment('支付配置');
            $table->unsignedTinyInteger('enabled')->default(0)->comment('是否启用');
            $table->unsignedTinyInteger('is_cod')->default(0)->comment('是否货到付款');
            $table->unsignedTinyInteger('is_online')->default(0)->comment('是否在线支付');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `payment` COMMENT '支付方式表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
