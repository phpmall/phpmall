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
        Schema::create('shop_cron', function (Blueprint $table) {
            $table->tinyIncrements('cron_id');
            $table->string('cron_code')->index()->comment('计划任务代码');
            $table->string('cron_name')->comment('计划任务名称');
            $table->text('cron_desc')->nullable()->comment('计划任务描述');
            $table->unsignedTinyInteger('cron_order')->default(0)->comment('排序');
            $table->text('cron_config')->nullable()->comment('计划任务配置');
            $table->integer('thistime')->default(0)->comment('本次执行时间');
            $table->integer('nextime')->index()->comment('下次执行时间');
            $table->tinyInteger('day')->comment('日');
            $table->string('week')->comment('周');
            $table->string('hour')->comment('时');
            $table->string('minute')->comment('分');
            $table->boolean('enable')->default(true)->index()->comment('是否启用');
            $table->boolean('run_once')->default(false)->comment('是否只运行一次');
            $table->string('allow_ip')->default('')->comment('允许的IP');
            $table->string('alow_files')->comment('允许的文件');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `shop_cron` COMMENT '计划任务表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_cron');
    }
};
