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
            $table->string('name', 100)->unique()->comment('角色标识');
            $table->string('display_name', 100)->comment('角色名称');
            $table->string('description', 255)->nullable()->comment('描述');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态：1-正常 2-禁用');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->timestamps();

            $table->comment('角色表');
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
