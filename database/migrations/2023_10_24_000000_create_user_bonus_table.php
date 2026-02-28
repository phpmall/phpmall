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
        Schema::create('user_bonus', function (Blueprint $table) {
            $table->increments('bonus_id');
            $table->unsignedTinyInteger('bonus_type_id')->default(0)->comment('红包类型ID');
            $table->unsignedBigInteger('bonus_sn')->default(0)->comment('红包序列号');
            $table->unsignedInteger('user_id')->default(0)->index()->comment('用户ID');
            $table->unsignedInteger('used_time')->default(0)->comment('使用时间');
            $table->unsignedInteger('order_id')->default(0)->comment('订单ID');
            $table->unsignedTinyInteger('emailed')->default(0)->comment('是否已发送邮件');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `user_bonus` COMMENT '用户红包表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_bonus');
    }
};
