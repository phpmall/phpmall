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
        Schema::create('order_delivery_order', function (Blueprint $table) {
            $table->increments('delivery_id');
            $table->string('delivery_sn')->comment('发货单号');
            $table->string('order_sn')->comment('订单号');
            $table->unsignedInteger('order_id')->default(0)->index()->comment('订单ID');
            $table->string('invoice_no')->nullable()->comment('物流单号');
            $table->unsignedInteger('add_time')->nullable()->default(0)->comment('添加时间');
            $table->unsignedTinyInteger('shipping_id')->nullable()->default(0)->comment('配送方式ID');
            $table->string('shipping_name')->nullable()->comment('配送方式名称');
            $table->unsignedInteger('user_id')->nullable()->default(0)->index()->comment('用户ID');
            $table->string('action_user')->nullable()->comment('操作用户');
            $table->string('consignee')->nullable()->comment('收货人');
            $table->string('address')->nullable()->comment('详细地址');
            $table->unsignedInteger('country')->nullable()->default(0)->comment('国家');
            $table->unsignedInteger('province')->nullable()->default(0)->comment('省份');
            $table->unsignedInteger('city')->nullable()->default(0)->comment('城市');
            $table->unsignedInteger('district')->nullable()->default(0)->comment('区县');
            $table->string('sign_building')->nullable()->comment('标志建筑');
            $table->string('email')->nullable()->comment('邮箱');
            $table->string('zipcode')->nullable()->comment('邮编');
            $table->string('tel')->nullable()->comment('电话');
            $table->string('mobile')->nullable()->comment('手机');
            $table->string('best_time')->nullable()->comment('最佳送货时间');
            $table->string('postscript')->nullable()->comment('附言');
            $table->string('how_oos')->nullable()->comment('缺货处理');
            $table->decimal('insure_fee')->unsigned()->nullable()->default(0)->comment('保价费用');
            $table->decimal('shipping_fee')->unsigned()->nullable()->default(0)->comment('配送费用');
            $table->unsignedInteger('update_time')->nullable()->default(0)->comment('更新时间戳');
            $table->integer('suppliers_id')->nullable()->default(0)->comment('供应商ID');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态');
            $table->unsignedInteger('agency_id')->nullable()->default(0)->comment('代理商ID');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `order_delivery_order` COMMENT '发货订单表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_delivery_order');
    }
};
