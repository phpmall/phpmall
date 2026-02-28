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
        Schema::create('shop_nav', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->index()->comment('类型');
            $table->string('ctype')->nullable()->comment('类别类型');
            $table->unsignedInteger('cid')->nullable()->comment('类别ID');
            $table->string('name')->comment('导航名称');
            $table->boolean('ifshow')->index()->comment('是否显示');
            $table->boolean('vieworder')->comment('显示顺序');
            $table->boolean('opennew')->comment('是否新窗口打开');
            $table->string('url')->comment('URL地址');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `shop_nav` COMMENT '导航菜单表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_nav');
    }
};
