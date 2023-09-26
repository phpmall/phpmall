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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('parent_id')->default(0)->comment('父级的ID');
            $table->unsignedInteger('in_station')->default(1)->comment('内容类型:1站内,2站外');
            $table->unsignedInteger('pattern_id')->comment('模型ID');
            $table->string('pattern_code')->comment('模型类型');
            $table->string('slug')->unique()->comment('URL PathInfo');
            $table->string('title')->unique()->comment('标题');
            $table->string('keywords')->default('')->comment('关键词');
            $table->string('description')->default('')->comment('描述');
            $table->string('author')->default('')->comment('编辑人员');
            $table->string('image')->default('')->comment('图片');
            $table->string('intro')->default('')->comment('简介');
            $table->text('content')->comment('描述');
            $table->text('extension')->comment('JSON内容扩展');
            $table->string('attachment', 1000)->default('')->comment('附件');
            $table->string('redirect_url', 1000)->default('')->comment('站外链接');
            $table->string('template_index')->default('')->comment('频道模板');
            $table->string('template_list')->default('')->comment('列表模板');
            $table->string('template_detail')->default('')->comment('详情模板');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->unsignedInteger('status')->default(1)->comment('状态:1正常,2禁用');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('内容表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
