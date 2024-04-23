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
        Schema::create('system_employee_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('system_employee_id')->nullable(false)->comment('员工ID');
            $table->unsignedInteger('system_permission_id')->nullable(false)->comment('权限资源ID');
            $table->unique(['system_employee_id', 'system_permission_id'], 'system_employee_permission_unique');
            $table->comment('系统员工资源权限表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_employee_permissions');
    }
};
