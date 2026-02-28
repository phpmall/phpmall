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
        Schema::create('article_cat', function (Blueprint $table) {
            $table->increments('cat_id');
            $table->unsignedInteger('parent_id')->default(0)->index()->comment('父级ID');
            $table->string('cat_name')->default('')->comment('分类名称');
            $table->unsignedTinyInteger('cat_type')->default(1)->index()->comment('分类类型');
            $table->string('keywords')->default('')->comment('关键词');
            $table->string('cat_desc')->default('')->comment('分类描述');
            $table->unsignedTinyInteger('sort_order')->default(50)->index()->comment('排序');
            $table->unsignedTinyInteger('show_in_nav')->default(0)->comment('是否在导航显示');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `article_cat` COMMENT '文章分类表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_cat');
    }
};
