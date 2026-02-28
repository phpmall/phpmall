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
        Schema::create('goods_type', function (Blueprint $table) {
            $table->increments('cat_id');
            $table->string('cat_name')->default('')->comment('分类名称');
            $table->unsignedTinyInteger('enabled')->default(1)->comment('是否启用');
            $table->string('attr_group')->comment('属性分组');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `goods_type` COMMENT '商品类型表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_type');
    }
};
