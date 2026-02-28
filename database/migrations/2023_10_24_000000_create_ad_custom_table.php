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
        Schema::create('ad_custom', function (Blueprint $table) {
            $table->increments('ad_id');
            $table->unsignedTinyInteger('ad_type')->default(1)->comment('广告类型');
            $table->string('ad_name')->nullable()->comment('广告名称');
            $table->unsignedInteger('add_time')->default(0)->comment('添加时间');
            $table->text('content')->nullable()->comment('广告内容');
            $table->string('url')->nullable()->comment('广告链接');
            $table->unsignedTinyInteger('ad_status')->default(0)->comment('广告状态');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `ad_custom` COMMENT '自定义广告表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_custom');
    }
};
