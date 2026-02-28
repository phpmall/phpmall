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
        Schema::create('goods_brand', function (Blueprint $table) {
            $table->increments('brand_id');
            $table->string('brand_name')->default('')->comment('品牌名称');
            $table->string('brand_logo')->default('')->comment('品牌Logo');
            $table->text('brand_desc')->nullable()->comment('品牌描述');
            $table->string('site_url')->default('')->comment('品牌网址');
            $table->unsignedTinyInteger('sort_order')->default(50)->comment('排序顺序');
            $table->unsignedTinyInteger('is_show')->default(1)->index()->comment('是否显示');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `goods_brand` COMMENT '商品品牌表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_brand');
    }
};
