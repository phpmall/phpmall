<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_log', function (Blueprint $table) {
            $table->increments('log_id');
            $table->unsignedInteger('log_time')->default(0)->index()->comment('日志时间');
            $table->unsignedTinyInteger('user_id')->default(0)->index()->comment('用户ID');
            $table->string('log_info')->default('')->comment('日志信息');
            $table->string('ip_address')->default('')->comment('IP地址');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `admin_log` COMMENT '管理员操作日志表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_log');
    }
};
