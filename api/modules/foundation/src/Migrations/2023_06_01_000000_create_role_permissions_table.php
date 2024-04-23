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
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('role_id')->comment('角色ID');
            $table->unsignedInteger('permission_id')->comment('权限ID');
            $table->unique(['role_id', 'permission_id'], 'role_permission_id');
            $table->comment('管理角色关联表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
