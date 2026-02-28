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
        Schema::create('goods_type_attribute', function (Blueprint $table) {
            $table->increments('attr_id');
            $table->unsignedInteger('cat_id')->default(0)->index()->comment('分类ID');
            $table->string('attr_name')->default('')->comment('属性名称');
            $table->unsignedTinyInteger('attr_input_type')->default(1)->comment('属性输入类型');
            $table->unsignedTinyInteger('attr_type')->default(1)->comment('属性类型');
            $table->text('attr_values')->nullable()->comment('属性值');
            $table->unsignedTinyInteger('attr_index')->default(0)->comment('属性索引');
            $table->unsignedTinyInteger('sort_order')->default(0)->comment('排序顺序');
            $table->unsignedTinyInteger('is_linked')->default(0)->comment('是否关联');
            $table->unsignedTinyInteger('attr_group')->default(0)->comment('属性分组');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `goods_type_attribute` COMMENT '商品类型属性表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_type_attribute');
    }
};
