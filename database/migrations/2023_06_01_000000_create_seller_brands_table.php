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
        Schema::create('seller_brands', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('seller_id')->comment('卖家ID');

            $table->timestamps();
            $table->softDeletes();
            $table->comment('卖家品牌表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_brands');
    }
};
