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
        Schema::create('ad_adsense', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('from_ad')->default(0)->index()->comment('广告ID');
            $table->string('referer')->default('')->comment('来源页面');
            $table->unsignedInteger('clicks')->default(0)->comment('点击次数');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `ad_adsense` COMMENT '广告联盟表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_adsense');
    }
};
