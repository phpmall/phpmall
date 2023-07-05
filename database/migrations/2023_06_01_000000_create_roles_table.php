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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['admin','seller','supplier'])->comment('模块类型');
            $table->string('name')->unique()->comment('角色名称');
            $table->string('description')->default('')->comment('角色描述');
            $table->unsignedInteger('sort')->default(0)->comment('角色排序');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态:1正常;2禁用');
            $table->timestamps();
            $table->comment('用户角色表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
