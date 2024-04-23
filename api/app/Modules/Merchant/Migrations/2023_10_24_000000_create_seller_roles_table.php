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
        Schema::create('seller_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id')->comment('商户管理员ID');
            $table->unsignedBigInteger('role_id')->comment('角色ID');
            $table->unique(['seller_id', 'role_id'], 'seller_role_id');
            $table->comment('商户管理员与角色关联表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_roles');
    }
};
