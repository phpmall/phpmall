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
        Schema::create('feedback', function (Blueprint $table) {
            $table->increments('msg_id');
            $table->unsignedInteger('parent_id')->default(0)->comment('父级ID');
            $table->unsignedInteger('user_id')->default(0)->index()->comment('用户ID');
            $table->string('user_name')->default('')->comment('用户名');
            $table->string('user_email')->default('')->comment('用户邮箱');
            $table->string('msg_title')->default('')->comment('留言标题');
            $table->unsignedTinyInteger('msg_type')->default(0)->comment('留言类型');
            $table->unsignedTinyInteger('msg_status')->default(0)->comment('留言状态');
            $table->text('msg_content')->nullable()->comment('留言内容');
            $table->unsignedInteger('msg_time')->default(0)->comment('留言时间');
            $table->string('message_img')->default('0')->comment('留言图片');
            $table->unsignedInteger('order_id')->default(0)->comment('订单ID');
            $table->unsignedTinyInteger('msg_area')->default(0)->comment('留言区域');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `feedback` COMMENT '用户反馈表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
