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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique()->comment('权限标识');
            $table->string('display_name', 100)->comment('权限名称');
            $table->string('description', 255)->nullable()->comment('描述');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级ID');
            $table->string('type', 20)->default('menu')->comment('类型：menu-菜单 button-按钮 api-接口');
            $table->string('route', 255)->nullable()->comment('路由/接口地址');
            $table->string('icon', 100)->nullable()->comment('图标');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态：1-正常 2-禁用');
            $table->timestamps();

            $table->index('parent_id');
            $table->comment('权限表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
