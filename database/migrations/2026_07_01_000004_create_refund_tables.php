<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // order_refunds (售后退款/退货)
        Schema::create('order_refunds', function (Blueprint $table): void {
            $table->id();
            $table->string('refund_no', 32)->unique()->comment('退款单号');
            $table->unsignedBigInteger('order_id')->comment('订单ID');
            $table->unsignedBigInteger('order_item_id')->nullable()->comment('订单商品项ID，可为空（整单退款）');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedBigInteger('merchant_id')->comment('商家ID');
            $table->unsignedTinyInteger('type')->comment('1=仅退款 2=退货退款 3=换货');
            $table->string('reason', 255)->comment('退款原因');
            $table->unsignedTinyInteger('reason_type')->comment('原因分类');
            $table->string('description', 500)->nullable()->comment('补充说明');
            $table->json('images')->nullable()->comment('凭证图片');
            $table->unsignedBigInteger('apply_amount')->comment('申请退款金额（分）');
            $table->unsignedBigInteger('refund_amount')->default(0)->comment('实际退款金额（分）');
            $table->unsignedTinyInteger('status')->default(0)->comment('0=待商家处理 1=商家同意 2=商家拒绝 3=退货中 4=平台介入 5=已退款 6=已拒绝 7=用户撤销');
            $table->string('merchant_remark', 255)->nullable()->comment('商家处理备注');
            $table->string('platform_remark', 255)->nullable()->comment('平台仲裁备注');
            $table->string('return_express_company', 50)->nullable()->comment('退货快递公司');
            $table->string('return_express_no', 50)->nullable()->comment('退货快递单号');
            $table->timestamp('return_ship_time')->nullable()->comment('用户退货发货时间');
            $table->timestamp('merchant_receipt_time')->nullable()->comment('商家收到退货时间');
            $table->timestamp('refund_time')->nullable()->comment('实际退款时间');
            $table->timestamps();

            $table->index('order_id', 'idx_order_refunds_order_id');
            $table->index('user_id', 'idx_order_refunds_user_id');
            $table->index(['merchant_id', 'status'], 'idx_order_refunds_merchant_id_status');
            $table->unique('refund_no', 'udx_order_refunds_refund_no');
        });

        // payment_refunds (退款记录)
        Schema::create('payment_refunds', function (Blueprint $table): void {
            $table->id();
            $table->string('refund_no', 32)->unique()->comment('退款单号');
            $table->unsignedBigInteger('payment_id')->comment('原支付记录ID');
            $table->unsignedBigInteger('order_id')->comment('订单ID');
            $table->unsignedBigInteger('order_refund_id')->comment('关联售后单');
            $table->unsignedBigInteger('amount')->comment('退款金额（分）');
            $table->unsignedTinyInteger('channel')->comment('原支付渠道');
            $table->unsignedTinyInteger('status')->default(0)->comment('0=待退款 1=退款中 2=成功 3=失败');
            $table->timestamp('refunded_at')->nullable()->comment('退款成功时间');
            $table->string('channel_refund_id', 100)->nullable()->comment('渠道退款单号');
            $table->string('failure_reason', 255)->nullable()->comment('失败原因');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_refunds');
        Schema::dropIfExists('order_refunds');
    }
};
