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
        Schema::create('shop_config', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->default(0)->index()->comment('父级ID');
            $table->string('code')->default('')->unique()->comment('配置编码');
            $table->string('type')->default('')->comment('配置类型');
            $table->string('store_range')->default('')->comment('存储范围');
            $table->string('store_dir')->default('')->comment('存储目录');
            $table->text('value')->nullable()->comment('配置值');
            $table->unsignedTinyInteger('sort_order')->default(1)->comment('排序顺序');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `shop_config` COMMENT '商店配置表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_config');
    }
};
