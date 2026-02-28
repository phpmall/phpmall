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
        Schema::create('vote', function (Blueprint $table) {
            $table->increments('vote_id');
            $table->string('vote_name')->default('')->comment('投票名称');
            $table->unsignedInteger('start_time')->default(0)->comment('开始时间');
            $table->unsignedInteger('end_time')->default(0)->comment('结束时间');
            $table->unsignedTinyInteger('can_multi')->default(0)->comment('是否多选');
            $table->unsignedInteger('vote_count')->default(0)->comment('投票次数');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `vote` COMMENT '投票表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vote');
    }
};
