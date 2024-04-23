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
        Schema::create('content_models', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('模型名称');
            $table->string('code')->unique()->comment('模型编码');
            $table->string('intro')->default('')->comment('模型描述');
            $table->mediumText('fields')->comment('模型附加字段');
            $table->unsignedTinyInteger('immutable')->default(2)->comment('系统模型:1是，2否');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态:1正常,2禁用');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('内容模型表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_models');
    }
};
