<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Trade Domain.
     */
    public function up(): void
    {
        // 1. 购物车表
        if (! Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $table) {
                $table->comment('购物车表');
                $table->id();
                $table->unsignedBigInteger('user_id')->comment('用户ID');
                $table->unsignedBigInteger('merchant_id')->comment('商家ID');
                $table->unsignedBigInteger('product_id')->comment('商品ID');
                $table->unsignedBigInteger('sku_id')->comment('SKU ID');
                $table->unsignedInteger('quantity')->default(1)->comment('数量');
                $table->unsignedTinyInteger('is_selected')->default(1)->comment('是否选中：0-未选中，1-已选中');
                $table->timestamps();

                $table->unique(['user_id', 'sku_id'], 'udx_carts_user_id_sku_id');
                $table->index('user_id', 'idx_carts_user_id');
                $table->index(['user_id', 'merchant_id'], 'idx_carts_user_id_merchant_id');
            });
        }

        // 2. 订单主表
        if (! Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->comment('订单表');
                $table->id();
                $table->string('order_no', 32)->comment('订单号');
                $table->unsignedBigInteger('user_id')->comment('用户ID');
                $table->unsignedBigInteger('merchant_id')->comment('商家ID');
                $table->unsignedBigInteger('parent_order_id')->nullable()->comment('父订单ID（拆单）');
                $table->unsignedTinyInteger('order_type')->default(1)->comment('订单类型：1-普通，2-秒杀，3-拼团，4-分销');
                $table->unsignedTinyInteger('status')->default(10)->comment('订单状态：10-待付款，20-已支付，30-待发货，40-已发货，50-待收货，60-已收货，70-已完成，80-已取消，90-退款中，100-已退款');
                $table->unsignedTinyInteger('pay_status')->default(0)->comment('支付状态：0-未支付，20-已支付，30-部分退款，100-全额退款');
                $table->unsignedTinyInteger('refund_status')->default(0)->comment('退款状态：0-无退款，10-退款申请中，20-退款中，30-已退款，40-拒绝退款');
                $table->unsignedBigInteger('product_amount')->comment('商品总金额（分）');
                $table->unsignedBigInteger('discount_amount')->default(0)->comment('优惠金额（分）');
                $table->unsignedBigInteger('freight_amount')->default(0)->comment('运费（分）');
                $table->unsignedBigInteger('pay_amount')->comment('实付金额（分）');
                $table->unsignedTinyInteger('pay_method')->nullable()->comment('支付方式：1-微信，2-支付宝，3-余额，4-银联');
                $table->timestamp('pay_time')->nullable()->comment('支付时间');
                $table->string('pay_transaction_id', 100)->nullable()->comment('第三方支付流水号');
                $table->timestamp('ship_time')->nullable()->comment('发货时间');
                $table->timestamp('receipt_time')->nullable()->comment('确认收货时间');
                $table->timestamp('cancel_time')->nullable()->comment('取消时间');
                $table->string('cancel_reason', 255)->nullable()->comment('取消原因');
                $table->timestamp('auto_receipt_time')->nullable()->comment('自动确认收货时间');
                $table->string('remark', 255)->nullable()->comment('用户备注');
                $table->unsignedTinyInteger('source')->default(1)->comment('订单来源：1-PC，2-H5，3-小程序，4-App');
                $table->string('seller_remark', 500)->nullable()->comment('商家备注');
                $table->timestamps();
                $table->softDeletes();

                $table->unique('order_no', 'udx_orders_order_no');
                $table->index(['user_id', 'status'], 'idx_orders_user_id_status');
                $table->index(['merchant_id', 'status'], 'idx_orders_merchant_id_status');
                $table->index(['status', 'pay_status'], 'idx_orders_status_pay_status');
                $table->index('created_at', 'idx_orders_created_at');
                $table->index('pay_time', 'idx_orders_pay_time');
            });
        }

        // 3. 订单商品明细表
        if (! Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->comment('订单商品明细表');
                $table->id();
                $table->unsignedBigInteger('order_id')->comment('订单ID');
                $table->unsignedBigInteger('product_id')->comment('商品ID');
                $table->unsignedBigInteger('sku_id')->comment('SKU ID');
                $table->unsignedBigInteger('merchant_id')->comment('商家ID');
                $table->string('product_title', 200)->comment('商品标题（快照）');
                $table->string('product_image', 500)->comment('商品主图（快照）');
                $table->json('sku_specs')->comment('规格快照');
                $table->unsignedBigInteger('price')->comment('下单时单价（分）');
                $table->unsignedInteger('quantity')->comment('数量');
                $table->unsignedBigInteger('total_amount')->comment('小计（分）');
                $table->unsignedBigInteger('discount_amount')->default(0)->comment('优惠分摊（分）');
                $table->unsignedBigInteger('refund_amount')->default(0)->comment('已退款金额（分）');
                $table->unsignedTinyInteger('refund_status')->default(0)->comment('售后状态：0-无售后，1-申请中，2-已退款，3-已拒绝');
                $table->unsignedTinyInteger('is_commented')->default(0)->comment('是否评价：0-未评价，1-已评价');
                $table->timestamps();

                $table->index('order_id', 'idx_order_items_order_id');
                $table->index('product_id', 'idx_order_items_product_id');
            });
        }

        // 4. 订单发货物流表
        if (! Schema::hasTable('order_shipments')) {
            Schema::create('order_shipments', function (Blueprint $table) {
                $table->comment('订单物流发货表');
                $table->id();
                $table->unsignedBigInteger('order_id')->comment('订单ID');
                $table->unsignedBigInteger('merchant_id')->comment('商家ID');
                $table->string('logistics_company', 100)->comment('物流公司');
                $table->string('tracking_no', 100)->comment('物流单号');
                $table->string('remark', 500)->nullable()->comment('发货备注');
                $table->timestamps();

                $table->index('order_id', 'idx_order_shipments_order_id');
                $table->index('merchant_id', 'idx_order_shipments_merchant_id');
            });
        }

        // 5. 订单售后退款表
        if (! Schema::hasTable('order_refunds')) {
            Schema::create('order_refunds', function (Blueprint $table) {
                $table->comment('订单退款售后表');
                $table->id();
                $table->string('refund_no', 32)->comment('退款单号');
                $table->unsignedBigInteger('order_id')->comment('订单ID');
                $table->unsignedBigInteger('order_item_id')->nullable()->comment('订单商品项ID，可为空（整单退款）');
                $table->unsignedBigInteger('user_id')->comment('用户ID');
                $table->unsignedBigInteger('merchant_id')->comment('商家ID');
                $table->unsignedTinyInteger('type')->comment('退款类型：1-仅退款，2-退货退款，3-换货');
                $table->string('reason', 255)->comment('退款原因');
                $table->unsignedTinyInteger('reason_type')->comment('原因分类');
                $table->string('description', 500)->nullable()->comment('补充说明');
                $table->json('images')->nullable()->comment('凭证图片');
                $table->unsignedBigInteger('apply_amount')->comment('申请退款金额（分）');
                $table->unsignedBigInteger('refund_amount')->default(0)->comment('实际退款金额（分）');
                $table->unsignedTinyInteger('status')->default(0)->comment('售后状态：0-待商家处理，1-商家同意，2-商家拒绝，3-退货中，4-平台介入，5-已退款，6-已拒绝，7-用户撤销');
                $table->string('merchant_remark', 255)->nullable()->comment('商家处理备注');
                $table->string('platform_remark', 255)->nullable()->comment('平台仲裁备注');
                $table->string('return_express_company', 50)->nullable()->comment('退货快递公司');
                $table->string('return_express_no', 50)->nullable()->comment('退货快递单号');
                $table->timestamp('return_ship_time')->nullable()->comment('用户退货发货时间');
                $table->timestamp('merchant_receipt_time')->nullable()->comment('商家收到退货时间');
                $table->timestamp('refund_time')->nullable()->comment('实际退款时间');
                $table->timestamps();

                $table->unique('refund_no', 'udx_order_refunds_refund_no');
                $table->index('order_id', 'idx_order_refunds_order_id');
                $table->index('user_id', 'idx_order_refunds_user_id');
                $table->index(['merchant_id', 'status'], 'idx_order_refunds_merchant_id_status');
            });
        }

        // 6. 支付记录表
        if (! Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->comment('支付流水表');
                $table->id();
                $table->string('payment_no', 32)->comment('支付单号');
                $table->unsignedBigInteger('order_id')->comment('订单ID');
                $table->unsignedBigInteger('user_id')->comment('用户ID');
                $table->unsignedBigInteger('amount')->comment('支付金额（分）');
                $table->unsignedTinyInteger('channel')->comment('支付渠道：1-微信，2-支付宝，3-余额，4-银联');
                $table->string('channel_app_id', 50)->nullable()->comment('渠道AppID');
                $table->unsignedTinyInteger('status')->default(0)->comment('支付状态：0-待支付，1-支付中，2-成功，3-失败，4-关闭');
                $table->timestamp('paid_at')->nullable()->comment('支付成功时间');
                $table->string('transaction_id', 100)->nullable()->comment('第三方支付流水号');
                $table->string('failure_reason', 255)->nullable()->comment('失败原因');
                $table->string('client_ip', 45)->nullable()->comment('支付IP');
                $table->timestamp('expired_at')->comment('支付过期时间');
                $table->json('notify_raw')->nullable()->comment('渠道回调原始数据');
                $table->timestamps();

                $table->unique('payment_no', 'udx_payments_payment_no');
                $table->index('order_id', 'idx_payments_order_id');
                $table->index('transaction_id', 'idx_payments_transaction_id');
                $table->index('status', 'idx_payments_status');
            });
        }

        // 7. 支付退款流水表
        if (! Schema::hasTable('payment_refunds')) {
            Schema::create('payment_refunds', function (Blueprint $table) {
                $table->comment('支付退款流水表');
                $table->id();
                $table->string('refund_no', 32)->comment('退款单号');
                $table->unsignedBigInteger('payment_id')->comment('原支付记录ID');
                $table->unsignedBigInteger('order_id')->comment('订单ID');
                $table->unsignedBigInteger('order_refund_id')->comment('关联售后单');
                $table->unsignedBigInteger('amount')->comment('退款金额（分）');
                $table->unsignedTinyInteger('channel')->comment('原支付渠道：1-微信，2-支付宝，3-余额，4-银联');
                $table->unsignedTinyInteger('status')->default(0)->comment('退款状态：0-待退款，1-退款中，2-成功，3-失败');
                $table->timestamp('refunded_at')->nullable()->comment('退款成功时间');
                $table->string('channel_refund_id', 100)->nullable()->comment('渠道退款单号');
                $table->string('failure_reason', 255)->nullable()->comment('失败原因');
                $table->timestamps();

                $table->unique('refund_no', 'udx_payment_refunds_refund_no');
                $table->index('payment_id', 'idx_payment_refunds_payment_id');
                $table->index('order_id', 'idx_payment_refunds_order_id');
                $table->index('status', 'idx_payment_refunds_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_refunds');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_refunds');
        Schema::dropIfExists('order_shipments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('carts');
    }
};
