<?php

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
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_user_id')->comment('卖家创始人ID');
            $table->string('company_name')->comment('企业名称');
            $table->string('company_address')->comment('企业地址');
            $table->string('legal_person')->comment('企业法人姓名');
            $table->string('business_license')->comment('企业营业执照号');
            $table->string('tax_registration')->comment('企业税务登记号');
            $table->string('opening_bank')->comment('开户银行');
            $table->string('bank_account')->comment('企业银行账户');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('卖家表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }
};
