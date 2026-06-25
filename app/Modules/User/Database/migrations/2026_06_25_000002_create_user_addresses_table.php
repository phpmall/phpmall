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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->string('contact_name', 100)->comment('联系人姓名');
            $table->string('contact_phone', 20)->comment('联系人手机');
            $table->string('province', 100)->comment('省');
            $table->string('city', 100)->comment('市');
            $table->string('district', 100)->comment('区/县');
            $table->string('detail', 255)->comment('详细地址');
            $table->string('zip_code', 20)->nullable()->comment('邮编');
            $table->unsignedTinyInteger('is_default')->default(0)->comment('是否默认：1-是 0-否');
            $table->timestamps();

            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->comment('用户收货地址表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
