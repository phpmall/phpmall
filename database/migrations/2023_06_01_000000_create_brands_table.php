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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('品牌的名称或商标');
            $table->string('first_letter')->comment('品牌名称的首字母');
            $table->string('logo')->comment('品牌的标识性Logo图片地址');
            $table->string('big_pic')->comment('专区大图');
            $table->text('brand_story')->comment('品牌故事');
            $table->unsignedInteger('factory_status')->comment('是否为品牌制造商：0->不是；1->是');
            $table->unsignedInteger('show_status')->comment('是否显示');
            $table->unsignedInteger('product_count')->comment('产品数量');
            $table->unsignedInteger('product_comment_count')->comment('产品评论数量');
            $table->unsignedInteger('sort')->comment('排序');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('品牌表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
