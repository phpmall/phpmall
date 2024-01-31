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
        Schema::create('merchant_brands', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('merchant_id')->comment('商户ID');

            $table->timestamps();
            $table->softDeletes();
            $table->comment('商户品牌表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_brands');
    }
};
