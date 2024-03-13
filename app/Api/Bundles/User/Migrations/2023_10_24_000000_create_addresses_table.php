<?php

namespace App\Bundles\UMS\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('member_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->string('recipient_name')->default('')->comment('收货人姓名');
            $table->string('phone_number')->comment('联系电话号码');
            $table->string('country')->comment('国家名称');
            $table->string('province')->comment('省名称');
            $table->string('city')->comment('城市名称');
            $table->string('district')->comment('区县名称');
            $table->string('address')->comment('详细地址');
            $table->string('postal_code')->comment('邮政编码');
            $table->unsignedTinyInteger('is_default')->default(0)->comment('默认地址:1是,0否');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('收货地址表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_addresses');
    }
};
