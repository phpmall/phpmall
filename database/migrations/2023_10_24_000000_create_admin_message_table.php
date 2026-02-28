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
        Schema::create('admin_message', function (Blueprint $table) {
            $table->increments('message_id');
            $table->unsignedTinyInteger('sender_id')->default(0)->comment('发送者ID');
            $table->unsignedTinyInteger('receiver_id')->default(0)->index()->comment('接收者ID');
            $table->unsignedInteger('sent_time')->default(0)->comment('发送时间');
            $table->unsignedInteger('read_time')->default(0)->comment('阅读时间');
            $table->unsignedTinyInteger('readed')->default(0)->comment('是否已读');
            $table->unsignedTinyInteger('deleted')->default(0)->comment('是否删除');
            $table->string('title')->default('')->comment('消息标题');
            $table->text('message')->nullable()->comment('消息内容');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');

            $table->index(['sender_id', 'receiver_id'], 'sender_id');
        });

        DB::statement("ALTER TABLE `admin_message` COMMENT '管理员消息表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_message');
    }
};
