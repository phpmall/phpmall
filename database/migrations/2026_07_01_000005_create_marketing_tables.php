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
        // coupons
        Schema::create('coupons', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('merchant_id')->nullable()->comment('NULL=平台券，有值=商家券');
            $table->string('name', 100)->comment('优惠券名称');
            $table->unsignedTinyInteger('type')->comment('1=满减券 2=折扣券 3=无门槛券 4=兑换券');
            $table->unsignedTinyInteger('scope')->comment('1=全平台 2=指定分类 3=指定商品 4=指定商家');
            $table->unsignedBigInteger('threshold_amount')->default(0)->comment('使用门槛（分），0=无门槛');
            $table->unsignedBigInteger('discount_amount')->default(0)->comment('优惠金额（分，type=1,3时）');
            $table->decimal('discount_rate', 3, 2)->nullable()->comment('折扣率（type=2时，0.85=85折）');
            $table->unsignedBigInteger('max_discount_amount')->nullable()->comment('折扣券最高优惠金额（分）');
            $table->unsignedInteger('total_quantity')->comment('总发放数量');
            $table->unsignedInteger('remaining_quantity')->comment('剩余数量');
            $table->unsignedTinyInteger('limit_per_user')->default(1)->comment('每人限领');
            $table->timestamp('start_time')->comment('生效时间');
            $table->timestamp('end_time')->comment('过期时间');
            $table->unsignedTinyInteger('status')->default(1)->comment('0=停用 1=启用');
            $table->timestamps();
            $table->softDeletes();

            $table->index('merchant_id', 'idx_coupons_merchant_id');
            $table->index(['status', 'start_time', 'end_time'], 'idx_coupons_status_start_time_end_time');
        });

        // user_coupons
        Schema::create('user_coupons', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedBigInteger('coupon_id')->comment('优惠券ID');
            $table->unsignedTinyInteger('status')->default(0)->comment('0=未使用 1=已使用 2=已过期 3=已作废');
            $table->unsignedBigInteger('used_order_id')->nullable()->comment('使用订单');
            $table->timestamp('used_at')->nullable()->comment('使用时间');
            $table->timestamp('claim_time')->comment('领取时间');
            $table->timestamp('expire_time')->comment('过期时间');
            $table->timestamp('created_at')->comment('创建时间');

            $table->index(['user_id', 'status'], 'idx_user_coupons_user_id_status');
            $table->index('coupon_id', 'idx_user_coupons_coupon_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_coupons');
        Schema::dropIfExists('coupons');
    }
};
