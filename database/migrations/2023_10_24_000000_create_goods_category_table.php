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
        Schema::create('goods_category', function (Blueprint $table) {
            $table->increments('cat_id');
            $table->string('cat_name')->default('')->comment('分类名称');
            $table->string('keywords')->nullable()->comment('关键词');
            $table->string('cat_desc')->nullable()->comment('分类描述');
            $table->unsignedInteger('parent_id')->default(0)->index()->comment('父级ID');
            $table->unsignedTinyInteger('sort_order')->default(50)->comment('排序');
            $table->string('template_file')->nullable()->comment('模板文件');
            $table->string('measure_unit')->default('')->comment('计量单位');
            $table->boolean('show_in_nav')->default(false)->comment('是否在导航显示');
            $table->string('style')->nullable()->comment('样式');
            $table->unsignedTinyInteger('is_show')->default(1)->comment('是否显示');
            $table->tinyInteger('grade')->default(0)->comment('等级');
            $table->string('filter_attr')->nullable()->comment('筛选属性');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');

            // 复合索引用于树形结构查询
            $table->index(['parent_id', 'sort_order']);
        });

        DB::statement("ALTER TABLE `goods_category` COMMENT '商品分类表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_category');
    }
};
