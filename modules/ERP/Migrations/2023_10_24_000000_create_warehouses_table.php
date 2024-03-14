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
        Schema::create('erp_warehouses', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('status')->comment('状态:1正常;2禁用');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('仓库表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('erp_warehouses');
    }
};
