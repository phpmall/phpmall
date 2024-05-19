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
        Schema::create('system_role_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('system_role_id')->nullable(false)->comment('系统员工角色ID');
            $table->unsignedInteger('system_permission_id')->nullable(false)->comment('系统权限资源ID');
            $table->unique(['system_role_id', 'system_permission_id'], 'system_role_permission_unique');
            $table->comment('系统员工角色资源权限表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_role_permissions');
    }
};
