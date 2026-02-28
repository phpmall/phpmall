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
        Schema::create('email_send', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->comment('邮箱地址');
            $table->integer('template_id')->comment('模板ID');
            $table->text('email_content')->nullable()->comment('邮件内容');
            $table->boolean('error')->default(false)->comment('是否错误');
            $table->tinyInteger('pri')->comment('优先级');
            $table->integer('last_send')->comment('最后发送时间');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `email_send` COMMENT '邮件发送记录表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_send');
    }
};
