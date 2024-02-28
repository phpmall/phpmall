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
        Schema::create('navigations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级ID');
            $table->enum('type', ['top', 'middle', 'bottom'])->default('middle')->comment('导航类型');
            $table->string('name')->comment('导航文字');
            $table->string('description')->default('')->comment('导航描述');
            $table->string('icon')->default('')->comment('小图标');
            $table->string('link')->default('')->comment('链接地址');
            $table->enum('target', ['_self', '_blank', '_top'])->default('_self')->comment('打开方式');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态:1正常,2禁用');
            $table->timestamps();
            $table->comment('导航表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navigations');
    }
};
