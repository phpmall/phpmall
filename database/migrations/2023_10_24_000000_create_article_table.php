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
        Schema::create('article', function (Blueprint $table) {
            $table->increments('article_id');
            $table->integer('cat_id')->default(0)->index()->comment('分类ID');
            $table->string('title')->default('')->comment('文章标题');
            $table->text('content')->nullable()->comment('文章内容');
            $table->string('author')->default('')->comment('作者');
            $table->string('author_email')->default('')->comment('作者邮箱');
            $table->string('keywords')->default('')->comment('关键词');
            $table->unsignedTinyInteger('article_type')->default(2)->comment('文章类型');
            $table->unsignedTinyInteger('is_open')->default(1)->comment('是否公开');
            $table->unsignedInteger('add_time')->default(0)->comment('添加时间');
            $table->string('file_url')->default('')->comment('文件地址');
            $table->unsignedTinyInteger('open_type')->default(0)->comment('打开方式');
            $table->string('link')->default('')->comment('链接地址');
            $table->string('description')->nullable()->comment('文章描述');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `article` COMMENT '文章表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article');
    }
};
