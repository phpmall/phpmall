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
        Schema::create('system_departments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('parent_id')->default(0)->comment('父级ID');
            $table->string('name')->comment('部门名称');
            $table->unsignedInteger('system_employee_id')->comment('部门负责人');
            $table->string('description')->default('')->nullable(false)->comment('描述');
            $table->unsignedInteger('sort')->nullable(false)->comment('排序');
            $table->unsignedTinyInteger('status')->nullable(false)->comment('状态:1正常,2禁用');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('系统部门表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_departments');
    }
};
