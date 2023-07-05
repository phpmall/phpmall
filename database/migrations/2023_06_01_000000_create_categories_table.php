<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->comment('上级分类的编号：0表示一级分类');
            $table->string('name')->comment('分类名称');
            $table->string('icon')->comment('图标');
            $table->string('keywords')->comment('关键字');
            $table->text('description')->comment('描述');
            $table->unsignedInteger('level')->comment('分类级别：0->1级；1->2级');
            $table->unsignedInteger('product_count')->comment('商品数量');
            $table->string('product_unit')->comment('商品单位');
            $table->unsignedInteger('nav_status')->comment('是否显示在导航栏：0->不显示；1->显示');
            $table->unsignedInteger('show_status')->comment('显示状态：0->不显示；1->显示');
            $table->unsignedInteger('sort')->comment('排序');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('商品分类表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
