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
        Schema::create('user_extend_fields', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('reg_field_name')->comment('注册字段名称');
            $table->unsignedTinyInteger('dis_order')->default(100)->comment('显示顺序');
            $table->unsignedTinyInteger('display')->default(1)->comment('是否显示');
            $table->unsignedTinyInteger('type')->default(0)->comment('类型');
            $table->unsignedTinyInteger('is_need')->default(1)->comment('是否必填');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `user_extend_fields` COMMENT '用户扩展字段表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_extend_fields');
    }
};
