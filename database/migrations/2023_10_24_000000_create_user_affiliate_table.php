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
        Schema::create('user_affiliate', function (Blueprint $table) {
            $table->increments('log_id');
            $table->integer('order_id')->comment('订单ID');
            $table->integer('time')->comment('时间');
            $table->integer('user_id')->comment('用户ID');
            $table->string('user_name')->nullable()->comment('用户名');
            $table->decimal('money')->default(0)->comment('金额');
            $table->integer('point')->default(0)->comment('积分');
            $table->boolean('separate_type')->default(false)->comment('分成类型');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `user_affiliate` COMMENT '用户推荐关系表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_affiliate');
    }
};
