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
        Schema::create('seller_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id')->comment('商户管理员ID');
            $table->enum('level', ['debug', 'info', 'warning', 'error'])->comment('日志级别');
            $table->string('message')->comment('日志内容');
            $table->string('user_agent')->comment('User Agent');
            $table->string('ip_address')->comment('IP地址');
            $table->timestamps();
            $table->comment('商户管理员日志表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_logs');
    }
};
