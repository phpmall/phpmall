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
        Schema::create('ad_position', function (Blueprint $table) {
            $table->tinyIncrements('position_id');
            $table->string('position_name')->default('')->comment('广告位名称');
            $table->unsignedInteger('ad_width')->default(0)->comment('广告宽度');
            $table->unsignedInteger('ad_height')->default(0)->comment('广告高度');
            $table->string('position_desc')->default('')->comment('广告位描述');
            $table->text('position_style')->nullable()->comment('广告位样式');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `ad_position` COMMENT '广告位置表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_position');
    }
};
