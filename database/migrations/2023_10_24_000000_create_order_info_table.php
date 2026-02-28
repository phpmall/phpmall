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
        Schema::create('order_info', function (Blueprint $table) {
            $table->increments('order_id');
            $table->string('order_sn')->default('')->unique()->comment('订单编号');
            $table->unsignedInteger('user_id')->default(0)->index()->comment('用户ID');
            $table->unsignedTinyInteger('order_status')->default(0)->index()->comment('订单状态');
            $table->unsignedTinyInteger('shipping_status')->default(0)->index()->comment('配送状态');
            $table->unsignedTinyInteger('pay_status')->default(0)->index()->comment('支付状态');
            $table->string('consignee')->default('')->comment('收货人');
            $table->unsignedInteger('country')->default(0)->comment('国家');
            $table->unsignedInteger('province')->default(0)->comment('省份');
            $table->unsignedInteger('city')->default(0)->comment('城市');
            $table->unsignedInteger('district')->default(0)->comment('区县');
            $table->string('address')->default('')->comment('详细地址');
            $table->string('zipcode')->default('')->comment('邮政编码');
            $table->string('tel')->default('')->comment('电话');
            $table->string('mobile')->default('')->comment('手机');
            $table->string('email')->default('')->comment('邮箱');
            $table->string('best_time')->nullable()->comment('最佳送货时间');
            $table->string('sign_building')->nullable()->comment('标志建筑');
            $table->string('postscript')->default('')->comment('订单附言');
            $table->unsignedInteger('shipping_id')->default(0)->index()->comment('配送方式ID');
            $table->string('shipping_name')->default('')->comment('配送方式名称');
            $table->unsignedInteger('pay_id')->default(0)->index()->comment('支付方式ID');
            $table->string('pay_name')->default('')->comment('支付方式名称');
            $table->string('how_oos')->nullable()->comment('缺货处理方式');
            $table->string('how_surplus')->nullable()->comment('余额处理方式');
            $table->string('pack_name')->nullable()->comment('包装名称');
            $table->string('card_name')->nullable()->comment('贺卡名称');
            $table->string('card_message')->nullable()->comment('贺卡内容');
            $table->string('inv_payee')->nullable()->comment('发票抬头');
            $table->string('inv_content')->nullable()->comment('发票内容');
            $table->decimal('goods_amount')->default(0)->comment('商品总金额');
            $table->decimal('shipping_fee')->default(0)->comment('配送费用');
            $table->decimal('insure_fee')->default(0)->comment('保价费用');
            $table->decimal('pay_fee')->default(0)->comment('支付费用');
            $table->decimal('pack_fee')->default(0)->comment('包装费用');
            $table->decimal('card_fee')->default(0)->comment('贺卡费用');
            $table->decimal('money_paid')->default(0)->comment('已付款金额');
            $table->decimal('surplus')->default(0)->comment('余额');
            $table->unsignedInteger('integral')->default(0)->comment('使用积分');
            $table->decimal('integral_money')->default(0)->comment('积分抵扣金额');
            $table->decimal('bonus')->default(0)->comment('红包金额');
            $table->decimal('order_amount')->default(0)->comment('订单总金额');
            $table->integer('from_ad')->default(0)->comment('来源广告ID');
            $table->string('referer')->default('')->comment('来源页面');
            $table->unsignedInteger('add_time')->default(0)->comment('添加时间');
            $table->unsignedInteger('confirm_time')->default(0)->comment('确认时间');
            $table->unsignedInteger('pay_time')->default(0)->comment('支付时间');
            $table->unsignedInteger('shipping_time')->default(0)->comment('配送时间');
            $table->unsignedTinyInteger('pack_id')->default(0)->comment('包装ID');
            $table->unsignedTinyInteger('card_id')->default(0)->comment('贺卡ID');
            $table->unsignedInteger('bonus_id')->default(0)->comment('红包ID');
            $table->string('invoice_no')->default('')->comment('发货单号');
            $table->string('extension_code')->default('')->comment('扩展代码');
            $table->unsignedInteger('extension_id')->default(0)->comment('扩展ID');
            $table->string('to_buyer')->nullable()->comment('给买家的留言');
            $table->string('pay_note')->nullable()->comment('付款备注');
            $table->unsignedInteger('agency_id')->nullable()->index()->comment('办事处ID');
            $table->string('inv_type')->nullable()->comment('发票类型');
            $table->decimal('tax')->nullable()->comment('税额');
            $table->boolean('is_separate')->default(false)->comment('是否拆分');
            $table->unsignedInteger('parent_id')->default(0)->comment('父订单ID');
            $table->decimal('discount')->nullable()->comment('折扣金额');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');

            // 复合索引
            $table->index(['user_id', 'order_status']);
            $table->index(['extension_code', 'extension_id']);
        });

        DB::statement("ALTER TABLE `order_info` COMMENT '订单信息表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_info');
    }
};
