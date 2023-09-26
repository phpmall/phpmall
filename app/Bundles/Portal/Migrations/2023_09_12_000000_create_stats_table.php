<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stats', function (Blueprint $table) {
            $table->id();
            $table->date('access_time')->comment('请求日期');
            $table->unsignedInteger('visit_times')->default(0)->comment('请求次数');
            $table->string('ip_address')->default('')->comment('IP地址');
            $table->string('system')->default('')->comment('操作系统');
            $table->string('browser')->default('')->comment('浏览器');
            $table->string('language')->default('')->comment('语言');
            $table->string('area')->default('')->comment('地区');
            $table->string('referer_domain')->default('')->comment('来源域名');
            $table->string('referer_path')->default('')->comment('来源地址');
            $table->string('access_url')->default('')->comment('请求url地址');
            $table->timestamps();
            $table->comment('统计表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stats');
    }
};
