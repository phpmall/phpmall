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
            $table->enum('type', ['admin','seller','supplier'])->comment('模块类型');
            $table->unsignedInteger('parent_id')->default(0)->comment('父级ID');
            $table->string('icon')->default('')->comment('权限图标');
            $table->string('name')->default('')->comment('权限名称');
            $table->string('description')->default('')->comment('权限描述');
            $table->string('code')->unique()->comment('权限路由');
            $table->boolean('menu')->default(0)->comment('是否为菜单项：1是,0否');
            $table->unsignedInteger('sort')->default(0)->comment('权限排序');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态:1正常;2禁用');
            $table->timestamps();
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
