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
        Schema::create('user_account_log', function (Blueprint $table) {
            $table->increments('log_id');
            $table->unsignedInteger('user_id')->index()->comment('用户ID');
            $table->decimal('user_money')->comment('用户余额');
            $table->decimal('frozen_money')->comment('冻结金额');
            $table->integer('rank_points')->comment('等级积分');
            $table->integer('pay_points')->comment('消费积分');
            $table->unsignedInteger('change_time')->comment('变更时间');
            $table->string('change_desc')->comment('变更描述');
            $table->unsignedTinyInteger('change_type')->comment('变更类型');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `user_account_log` COMMENT '用户账户日志表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_account_log');
    }
};
