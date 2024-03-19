<?php

declare(strict_types=1);

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
            $table->unsignedInteger('parent_id')->default(0)->comment('父级ID');
            $table->string('module')->default('')->comment('模块名:如manager,merchant');
            $table->string('icon')->default('')->comment('菜单图标');
            $table->string('name')->default('')->comment('资源名称');
            $table->string('resource')->unique('resource_unique')->comment('资源标识');
            $table->unsignedTinyInteger('menu')->default(0)->comment('是否为菜单项:1是,0否');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->unsignedTinyInteger('status')->comment('状态:1正常,2禁用');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('权限资源表');
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
