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
        Schema::create('ad', function (Blueprint $table) {
            $table->increments('ad_id');
            $table->unsignedInteger('position_id')->default(0)->index()->comment('广告位置ID');
            $table->unsignedTinyInteger('media_type')->default(0)->comment('媒体类型');
            $table->string('ad_name')->default('')->comment('广告名称');
            $table->string('ad_link')->default('')->comment('广告链接');
            $table->text('ad_code')->nullable()->comment('广告代码');
            $table->integer('start_time')->default(0)->comment('开始时间');
            $table->integer('end_time')->default(0)->comment('结束时间');
            $table->string('link_man')->default('')->comment('联系人');
            $table->string('link_email')->default('')->comment('联系邮箱');
            $table->string('link_phone')->default('')->comment('联系电话');
            $table->unsignedInteger('click_count')->default(0)->comment('点击次数');
            $table->unsignedTinyInteger('enabled')->default(1)->index()->comment('是否启用');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `ad` COMMENT '广告表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad');
    }
};
