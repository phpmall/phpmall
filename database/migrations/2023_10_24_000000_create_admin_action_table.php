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
        Schema::create('admin_action', function (Blueprint $table) {
            $table->tinyIncrements('action_id');
            $table->unsignedTinyInteger('parent_id')->default(0)->index()->comment('父级ID');
            $table->string('action_code')->default('')->comment('权限代码');
            $table->string('relevance')->default('')->comment('关联信息');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `admin_action` COMMENT '管理员操作权限表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_action');
    }
};
