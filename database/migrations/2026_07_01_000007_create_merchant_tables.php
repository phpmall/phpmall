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
        // merchants
        Schema::create('merchants', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 100)->comment('店铺名称');
            $table->string('logo_url', 500)->nullable()->comment('店铺Logo');
            $table->string('cover_url', 500)->nullable()->comment('店铺封面');
            $table->text('description')->nullable()->comment('店铺简介');
            $table->string('contact_phone', 20)->comment('联系手机');
            $table->string('contact_name', 50)->comment('联系人');
            $table->string('business_license_no', 50)->nullable()->comment('营业执照号');
            $table->string('business_license_url', 500)->nullable()->comment('营业执照图片');
            $table->string('legal_person_name', 50)->nullable()->comment('法人姓名');
            $table->string('legal_person_id_card', 18)->nullable()->comment('法人身份证（AES）');
            $table->unsignedTinyInteger('settlement_cycle')->default(7)->comment('结算周期 T+N 天');
            $table->decimal('settlement_rate', 5, 4)->default(0.0500)->comment('平台抽成比例（5%）');
            $table->unsignedTinyInteger('status')->default(0)->comment('0=待审核 1=正常 2=冻结 3=关闭');
            $table->unsignedTinyInteger('audit_status')->default(0)->comment('0=待审核 1=通过 2=拒绝');
            $table->string('audit_remark', 500)->nullable()->comment('审核备注');
            $table->string('frozen_reason', 500)->nullable()->comment('冻结原因');
            $table->timestamp('frozen_until')->nullable()->comment('冻结截止时间');
            $table->unsignedBigInteger('total_sales_amount')->default(0)->comment('累计销售额（分）');
            $table->unsignedInteger('total_order_count')->default(0)->comment('累计订单数');
            $table->timestamps();
            $table->softDeletes();

            $table->index('status', 'idx_merchants_status');
            $table->index('audit_status', 'idx_merchants_audit_status');
            $table->index('created_at', 'idx_merchants_created_at');
        });

        // merchant_staffs
        Schema::create('merchant_staffs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('merchant_id')->comment('商家ID');
            $table->string('username', 50)->comment('登录名');
            $table->string('password_hash', 255)->comment('bcrypt 密码哈希');
            $table->string('real_name', 50)->nullable()->comment('姓名');
            $table->string('phone', 20)->nullable()->comment('手机号');
            $table->unsignedTinyInteger('status')->default(1)->comment('0=禁用 1=正常');
            $table->timestamp('last_login_at')->nullable()->comment('最后登录时间');
            $table->timestamps();
            $table->softDeletes();

            $table->index('merchant_id', 'idx_merchant_staffs_merchant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_staffs');
        Schema::dropIfExists('merchants');
    }
};
