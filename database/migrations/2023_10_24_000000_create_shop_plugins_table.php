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
        Schema::create('shop_plugins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->default('')->unique()->comment('插件编码');
            $table->string('version')->default('')->comment('版本号');
            $table->string('library')->default('')->comment('库名');
            $table->unsignedTinyInteger('assign')->default(0)->comment('分配状态');
            $table->unsignedInteger('install_date')->default(0)->comment('安装日期');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `shop_plugins` COMMENT '插件表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_plugins');
    }
};
