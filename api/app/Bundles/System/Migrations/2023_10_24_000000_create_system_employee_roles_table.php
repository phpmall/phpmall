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
        Schema::create('system_employee_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('system_employee_id')->comment('用户ID');
            $table->unsignedBigInteger('system_role_id')->comment('角色ID');
            $table->unique(['system_employee_id', 'system_role_id'], 'system_employee_role_unique');
            $table->comment('系统员工与角色关联表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_employee_roles');
    }
};
