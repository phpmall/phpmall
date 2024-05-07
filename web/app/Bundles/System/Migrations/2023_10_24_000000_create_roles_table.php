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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false)->comment('角色名称');
            $table->string('code')->unique('code_unique')->nullable(false)->comment('角色代码');
            $table->string('description')->default('')->nullable(false)->comment('角色描述');
            $table->unsignedInteger('sort')->nullable(false)->comment('排序');
            $table->unsignedTinyInteger('status')->nullable(false)->comment('状态:1正常,2禁用');
            $table->timestamps();
            $table->softDeletes();
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
