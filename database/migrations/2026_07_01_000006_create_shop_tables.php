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
        // shops
        Schema::create('shops', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('merchant_id')->comment('商家ID');
            $table->string('name', 100)->comment('店铺名称');
            $table->string('logo_url', 500)->nullable()->comment('店铺Logo');
            $table->string('cover_url', 500)->nullable()->comment('店铺封面');
            $table->text('description')->nullable()->comment('店铺简介');
            $table->string('contact_phone', 20)->comment('联系手机');
            $table->string('contact_name', 50)->comment('联系人');
            $table->unsignedTinyInteger('status')->default(0)->comment('0=待审核 1=正常 2=冻结 3=关闭');
            $table->unsignedTinyInteger('audit_status')->default(0)->comment('0=待审核 1=通过 2=拒绝');
            $table->string('audit_remark', 500)->nullable()->comment('审核备注');
            $table->string('frozen_reason', 500)->nullable()->comment('冻结原因');
            $table->timestamp('frozen_until')->nullable()->comment('冻结截止时间');
            $table->unsignedBigInteger('total_sales_amount')->default(0)->comment('累计销售额（分）');
            $table->unsignedInteger('total_order_count')->default(0)->comment('累计订单数');
            $table->timestamps();
            $table->softDeletes();

            $table->index('merchant_id', 'idx_shops_merchant_id');
            $table->index('status', 'idx_shops_status');
            $table->index('audit_status', 'idx_shops_audit_status');
            $table->index('created_at', 'idx_shops_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
