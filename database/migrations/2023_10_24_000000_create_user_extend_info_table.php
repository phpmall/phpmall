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
        Schema::create('user_extend_info', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->unsignedInteger('reg_field_id')->comment('注册字段ID');
            $table->text('content')->nullable()->comment('内容');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `user_extend_info` COMMENT '用户扩展信息表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_extend_info');
    }
};
