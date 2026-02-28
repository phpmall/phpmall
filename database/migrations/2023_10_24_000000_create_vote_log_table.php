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
        Schema::create('vote_log', function (Blueprint $table) {
            $table->increments('log_id');
            $table->unsignedInteger('vote_id')->default(0)->index()->comment('投票ID');
            $table->string('ip_address')->default('')->comment('IP地址');
            $table->unsignedInteger('vote_time')->default(0)->comment('投票时间');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `vote_log` COMMENT '投票记录表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vote_log');
    }
};
