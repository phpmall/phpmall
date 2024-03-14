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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('parent_id')->default(0)->comment('父节点id');
            $table->string('name')->default('')->comment('配置名称');
            $table->string('code')->unique()->comment('配置code');
            $table->string('type')->default('')->comment('配置类型：text、select、file、hidden等');
            $table->string('range')->default('')->comment('配置数组索引');
            $table->string('value')->default('')->comment('该项配置的值');
            $table->unsignedInteger('sort')->default(1)->comment('排序');
            $table->comment('配置表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
