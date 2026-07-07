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
        Schema::create('order_shipments', function (Blueprint $table): void {
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_shipments');
    }
};
