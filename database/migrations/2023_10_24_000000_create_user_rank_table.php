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
        Schema::create('user_rank', function (Blueprint $table) {
            $table->tinyIncrements('rank_id');
            $table->string('rank_name')->default('')->comment('等级名称');
            $table->unsignedInteger('min_points')->default(0)->comment('最小积分');
            $table->unsignedInteger('max_points')->default(0)->comment('最大积分');
            $table->unsignedTinyInteger('discount')->default(0)->comment('折扣');
            $table->unsignedTinyInteger('show_price')->default(1)->comment('是否显示价格');
            $table->unsignedTinyInteger('special_rank')->default(0)->comment('是否特殊等级');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `user_rank` COMMENT '用户等级表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_rank');
    }
};
