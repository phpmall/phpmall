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
        // payments
        Schema::create('payments', function (Blueprint $table): void {
            $table->id();
            $table->string('payment_no', 32)->comment('支付单号');
            $table->unsignedBigInteger('order_id')->comment('订单ID');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedBigInteger('amount')->comment('支付金额（分）');
            $table->unsignedTinyInteger('channel')->comment('1=微信 2=支付宝 3=余额 4=银联');
            $table->string('channel_app_id', 50)->nullable()->comment('渠道AppID');
            $table->unsignedTinyInteger('status')->default(0)->comment('0=待支付 1=支付中 2=成功 3=失败 4=关闭');
            $table->timestamp('paid_at')->nullable()->comment('支付成功时间');
            $table->string('transaction_id', 100)->nullable()->comment('第三方支付流水号');
            $table->string('failure_reason', 255)->nullable()->comment('失败原因');
            $table->string('client_ip', 45)->nullable()->comment('支付IP');
            $table->timestamp('expired_at')->comment('支付过期时间');
            $table->json('notify_raw')->nullable()->comment('渠道回调原始数据');
            $table->timestamps();

            $table->index('order_id', 'idx_payments_order_id');
            $table->unique('payment_no', 'udx_payments_payment_no');
            $table->index('transaction_id', 'idx_payments_transaction_id');
            $table->index('status', 'idx_payments_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
