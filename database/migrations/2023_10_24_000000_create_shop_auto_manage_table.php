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
        Schema::create('shop_auto_manage', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->comment('项目ID');
            $table->string('type')->comment('类型');
            $table->integer('starttime')->comment('开始时间');
            $table->integer('endtime')->comment('结束时间');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');

            $table->unique(['item_id', 'type'], 'item_id_type');
        });

        DB::statement("ALTER TABLE `shop_auto_manage` COMMENT '自动管理表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_auto_manage');
    }
};
