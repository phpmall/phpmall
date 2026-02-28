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
        Schema::create('email_template', function (Blueprint $table) {
            $table->tinyIncrements('template_id');
            $table->string('type')->index()->comment('类型');
            $table->string('template_code')->default('')->unique()->comment('模板代码');
            $table->unsignedTinyInteger('is_html')->default(0)->comment('是否HTML格式');
            $table->string('template_subject')->default('')->comment('模板主题');
            $table->text('template_content')->nullable()->comment('模板内容');
            $table->unsignedInteger('last_modify')->default(0)->comment('最后修改时间');
            $table->unsignedInteger('last_send')->default(0)->comment('最后发送时间');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `email_template` COMMENT '邮件模板表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_template');
    }
};
