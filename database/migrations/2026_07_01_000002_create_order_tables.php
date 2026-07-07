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
        // orders
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->string('order_no', 32)->comment('订单号');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedBigInteger('merchant_id')->comment('商家ID');
            $table->unsignedBigInteger('parent_order_id')->nullable()->comment('父订单ID（拆单）');
            $table->unsignedTinyInteger('order_type')->default(1)->comment('1=普通 2=秒杀 3=拼团 4=分销');
            $table->unsignedTinyInteger('status')->default(10)->comment('10=待付款 20=已支付 30=待发货 40=已发货 50=待收货 60=已收货 70=已完成 80=已取消 90=退款中 100=已退款');
            $table->unsignedTinyInteger('pay_status')->default(0)->comment('0=未支付 20=已支付 30=部分退款 100=全额退款');
            $table->unsignedTinyInteger('refund_status')->default(0)->comment('0=无退款 10=退款申请中 20=退款中 30=已退款 40=拒绝退款');
            $table->unsignedBigInteger('product_amount')->comment('商品总金额（分）');
            $table->unsignedBigInteger('discount_amount')->default(0)->comment('优惠金额（分）');
            $table->unsignedBigInteger('freight_amount')->default(0)->comment('运费（分）');
            $table->unsignedBigInteger('pay_amount')->comment('实付金额（分）');
            $table->unsignedTinyInteger('pay_method')->nullable()->comment('1=微信 2=支付宝 3=余额 4=银联');
            $table->timestamp('pay_time')->nullable()->comment('支付时间');
            $table->string('pay_transaction_id', 100)->nullable()->comment('第三方支付流水号');
            $table->timestamp('ship_time')->nullable()->comment('发货时间');
            $table->timestamp('receipt_time')->nullable()->comment('确认收货时间');
            $table->timestamp('cancel_time')->nullable()->comment('取消时间');
            $table->string('cancel_reason', 255)->nullable()->comment('取消原因');
            $table->timestamp('auto_receipt_time')->nullable()->comment('自动确认收货时间');
            $table->string('remark', 255)->nullable()->comment('用户备注');
            $table->unsignedTinyInteger('source')->default(1)->comment('来源 1=PC 2=H5 3=小程序 4=App');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status'], 'idx_orders_user_id_status');
            $table->index(['merchant_id', 'status'], 'idx_orders_merchant_id_status');
            $table->unique('order_no', 'udx_orders_order_no');
            $table->index(['status', 'pay_status'], 'idx_orders_status_pay_status');
            $table->index('created_at', 'idx_orders_created_at');
            $table->index('pay_time', 'idx_orders_pay_time');
        });

        // order_items
        Schema::create('order_items', function (Blueprint $table): void {
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
            $table->unsignedTinyInteger('refund_status')->default(0)->comment('0=无 1=申请中 2=已退款 3=拒绝');
            $table->unsignedTinyInteger('is_commented')->default(0)->comment('是否已评价');
            $table->timestamps();

            $table->index('order_id', 'idx_order_items_order_id');
            $table->index('product_id', 'idx_order_items_product_id');
        });

        // carts
        Schema::create('carts', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedBigInteger('merchant_id')->comment('商家ID');
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('sku_id')->comment('SKU ID');
            $table->unsignedInteger('quantity')->default(1)->comment('数量');
            $table->unsignedTinyInteger('is_selected')->default(1)->comment('是否选中');
            $table->timestamps();

            $table->index('user_id', 'idx_carts_user_id');
            $table->index(['user_id', 'merchant_id'], 'idx_carts_user_id_merchant_id');
            $table->unique(['user_id', 'sku_id'], 'udx_carts_user_id_sku_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
