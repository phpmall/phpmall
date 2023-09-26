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
        Schema::create('user_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->enum('level', ['debug', 'info', 'warning', 'error'])->comment('日志级别');
            $table->string('message')->comment('日志内容');
            $table->string('user_agent')->comment('User Agent');
            $table->string('ip_address')->comment('IP地址');
            $table->timestamps();
            $table->comment('用户日志表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_logs');
    }
};
