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
        Schema::create('search_engine', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->nullable()->comment('日期');
            $table->string('search_engine')->default('')->comment('搜索引擎');
            $table->unsignedInteger('count')->default(0)->comment('次数');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');

            $table->unique(['date', 'search_engine'], 'date_search_engine');
        });

        DB::statement("ALTER TABLE `search_engine` COMMENT '搜索引擎表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_engine');
    }
};
