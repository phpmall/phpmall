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
        Schema::create('cms_advertising', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('parent_id')->default(0)->comment('类型:0广告位,其他为广告内容');
            $table->string('name')->default('')->comment('标题');
            $table->string('description')->default('')->comment('描述');
            $table->unsignedInteger('width')->default(0)->comment('广告宽度');
            $table->unsignedInteger('height')->default(0)->comment('广告高度');
            $table->string('link')->default('')->comment('链接地址');
            $table->string('code')->default('')->comment('广告内容');
            $table->dateTime('start_time')->comment('开始时间');
            $table->dateTime('end_time')->comment('结束时间');
            $table->unsignedInteger('click_count')->default(0)->comment('点击量');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->unsignedInteger('status')->default(1)->comment('状态:1正常,2禁用');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('广告表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_advertising');
    }
};
