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
        Schema::create('comment', function (Blueprint $table) {
            $table->increments('comment_id');
            $table->unsignedTinyInteger('comment_type')->default(0)->comment('评论类型');
            $table->unsignedInteger('id_value')->default(0)->index()->comment('关联ID');
            $table->string('email')->default('')->comment('邮箱');
            $table->string('user_name')->default('')->comment('用户名');
            $table->text('content')->nullable()->comment('内容');
            $table->unsignedTinyInteger('comment_rank')->default(0)->comment('评论等级');
            $table->unsignedInteger('add_time')->default(0)->comment('添加时间');
            $table->string('ip_address')->default('')->comment('IP地址');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态');
            $table->unsignedInteger('parent_id')->default(0)->index()->comment('父级ID');
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `comment` COMMENT '评论表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment');
    }
};
