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
        Schema::create('vote_option', function (Blueprint $table) {
            $table->increments('option_id');
            $table->unsignedInteger('vote_id')->default(0)->index()->comment('投票ID');
            $table->string('option_name')->default('')->comment('选项名称');
            $table->unsignedInteger('option_count')->default(0)->comment('选项票数');
            $table->unsignedTinyInteger('option_order')->default(100)->comment('选项顺序');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `vote_option` COMMENT '投票选项表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vote_option');
    }
};
