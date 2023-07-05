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
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_type_id')->comment('商品属性分类id');
            $table->string('name')->comment('名称');
            $table->unsignedInteger('select_type')->comment('属性选择类型：0->唯一；1->单选；2->多选；对应属性和参数意义不同；');
            $table->unsignedInteger('input_type')->comment('属性录入方式：0->手工录入；1->从列表中选取');
            $table->string('input_list')->comment('可选值列表，以逗号隔开');
            $table->unsignedInteger('sort')->comment('排序字段：最高的可以单独上传图片');
            $table->unsignedInteger('filter_type')->comment('分类筛选样式：1->普通；1->颜色');
            $table->unsignedInteger('search_type')->comment('检索类型；0->不需要进行检索；1->关键字检索；2->范围检索');
            $table->unsignedInteger('related_status')->comment('相同属性产品是否关联；0->不关联；1->关联');
            $table->unsignedInteger('hand_add_status')->comment('是否支持手动新增；0->不支持；1->支持');
            $table->unsignedInteger('type')->comment('属性的类型；0->规格；1->参数');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};
