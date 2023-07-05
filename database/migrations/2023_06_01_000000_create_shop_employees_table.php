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
        Schema::create('shop_employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id')->comment('卖家id');
            $table->unsignedBigInteger('shop_id')->comment('店铺id');

            $table->timestamps();
            $table->softDeletes();
            $table->comment('店铺员工表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_employees');
    }
};
