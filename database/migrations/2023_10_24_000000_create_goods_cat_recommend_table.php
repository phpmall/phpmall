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
        Schema::create('goods_cat_recommend', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cat_id')->comment('分类ID');
            $table->boolean('recommend_type')->comment('推荐类型');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');

            $table->unique(['cat_id', 'recommend_type'], 'cat_id_recommend_type');
        });

        DB::statement("ALTER TABLE `goods_cat_recommend` COMMENT '商品分类推荐表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_cat_recommend');
    }
};
