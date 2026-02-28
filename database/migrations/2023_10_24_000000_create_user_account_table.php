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
        Schema::create('user_account', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0)->index()->comment('用户ID');
            $table->string('admin_user')->comment('管理员');
            $table->decimal('amount')->comment('金额');
            $table->integer('add_time')->default(0)->comment('添加时间');
            $table->integer('paid_time')->default(0)->comment('支付时间');
            $table->string('admin_note')->comment('管理员备注');
            $table->string('user_note')->comment('用户备注');
            $table->boolean('process_type')->default(false)->comment('处理类型');
            $table->string('payment')->comment('支付方式');
            $table->boolean('is_paid')->default(false)->index()->comment('是否已支付');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `user_account` COMMENT '用户账户表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_account');
    }
};
