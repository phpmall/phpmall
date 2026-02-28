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
        Schema::create('shop_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('access_time')->default(0)->index()->comment('访问时间');
            $table->string('ip_address')->default('')->comment('IP地址');
            $table->unsignedInteger('visit_times')->default(1)->comment('访问次数');
            $table->string('browser')->default('')->comment('浏览器');
            $table->string('system')->default('')->comment('操作系统');
            $table->string('language')->default('')->comment('语言');
            $table->string('area')->default('')->comment('地区');
            $table->string('referer_domain')->default('')->comment('来源域名');
            $table->string('referer_path')->default('')->comment('来源路径');
            $table->string('access_url')->default('')->comment('访问URL');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `shop_stats` COMMENT '统计表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_stats');
    }
};
