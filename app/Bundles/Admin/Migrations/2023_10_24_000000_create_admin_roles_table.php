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
        Schema::create('admin_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('admin_user_id')->comment('用户ID');
            $table->unsignedInteger('role_id')->comment('角色ID');
            $table->unique(['admin_user_id', 'role_id'], 'admin_user_role_id');
            $table->comment('管理员与角色关联表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_roles');
    }
};
