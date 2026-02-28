<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('template', function (Blueprint $table) {
            $table->increments('id')->comment('ID');
            $table->string('filename')->default('')->comment('文件名');
            $table->string('region')->default('')->comment('区域');
            $table->string('library')->default('')->comment('库');
            $table->unsignedTinyInteger('sort_order')->default(0)->comment('排序顺序');
            $table->unsignedInteger('id_value')->default(0)->comment('关联ID');
            $table->unsignedTinyInteger('number')->default(5)->comment('数量');
            $table->unsignedTinyInteger('type')->default(0)->comment('类型');
            $table->string('theme')->default('')->index()->comment('主题');
            $table->string('remarks')->default('')->index()->comment('备注');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');

            $table->index(['filename', 'region'], 'filename');
        });

        DB::statement("ALTER TABLE `template` COMMENT '模板表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template');
    }
};
