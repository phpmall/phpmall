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
        Schema::create('user_booking', function (Blueprint $table) {
            $table->increments('rec_id');
            $table->unsignedInteger('user_id')->default(0)->index()->comment('用户ID');
            $table->string('email')->default('')->comment('邮箱');
            $table->string('link_man')->default('')->comment('联系人');
            $table->string('tel')->default('')->comment('电话');
            $table->unsignedInteger('goods_id')->default(0)->comment('商品ID');
            $table->string('goods_desc')->default('')->comment('商品描述');
            $table->unsignedInteger('goods_number')->default(0)->comment('商品数量');
            $table->unsignedInteger('booking_time')->default(0)->comment('预定时间');
            $table->unsignedTinyInteger('is_dispose')->default(0)->comment('是否处理');
            $table->string('dispose_user')->default('')->comment('处理用户');
            $table->unsignedInteger('dispose_time')->default(0)->comment('处理时间');
            $table->string('dispose_note')->default('')->comment('处理备注');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `user_booking` COMMENT '用户缺货登记表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_booking');
    }
};
