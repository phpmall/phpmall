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
            $table->string('guard')->comment('守卫模块');
            $table->unsignedInteger('parent_id')->default(0)->comment('父级ID');
            $table->string('name')->default('')->comment('名称');
            $table->string('description')->default('')->comment('描述');
            $table->string('path')->unique()->comment('标识');
            $table->string('icon')->default('')->comment('ICON图标');
            $table->boolean('type')->default(2)->comment('类型：1菜单,2页面,3接口');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->unsignedInteger('status')->default(1)->comment('状态:1正常,2禁用');
            $table->timestamps();
            $table->comment('管理权限表');
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
