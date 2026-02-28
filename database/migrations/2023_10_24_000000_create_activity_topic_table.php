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
        Schema::create('activity_topic', function (Blueprint $table) {
            $table->increments('topic_id')->comment('专题ID');
            $table->string('title')->default('')->comment('标题');
            $table->text('intro')->nullable()->comment('简介');
            $table->integer('start_time')->default(0)->comment('开始时间');
            $table->integer('end_time')->default(0)->comment('结束时间');
            $table->text('data')->nullable()->comment('数据');
            $table->string('template')->default('')->comment('模板');
            $table->text('css')->nullable()->comment('CSS样式');
            $table->string('topic_img')->nullable()->comment('主题图片');
            $table->string('title_pic')->nullable()->comment('标题图片');
            $table->string('base_style')->nullable()->comment('基础样式');
            $table->text('htmls')->nullable()->comment('HTML内容');
            $table->string('keywords')->nullable()->comment('关键词');
            $table->string('description')->nullable()->comment('描述');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `activity_topic` COMMENT '专题活动表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_topic');
    }
};
