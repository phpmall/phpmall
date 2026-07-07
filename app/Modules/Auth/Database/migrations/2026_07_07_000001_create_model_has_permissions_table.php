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
        Schema::create('model_has_permissions', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('permission_id')->comment('权限ID');
            $table->string('model_type', 255)->comment('模型类型');
            $table->unsignedBigInteger('model_id')->comment('模型ID');
            $table->timestamps();

            $table->unique(['permission_id', 'model_type', 'model_id'], 'udx_model_has_permissions');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->index(['model_type', 'model_id'], 'idx_model_has_permissions_model');

            $table->comment('模型直接权限关联表（spatie 风格）');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_has_permissions');
    }
};
