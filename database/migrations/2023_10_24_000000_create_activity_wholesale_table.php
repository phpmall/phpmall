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
        Schema::create('activity_wholesale', function (Blueprint $table) {
            $table->increments('act_id');
            $table->unsignedInteger('goods_id')->index()->comment('商品ID');
            $table->string('goods_name')->comment('商品名称');
            $table->string('rank_ids')->comment('等级ID');
            $table->text('prices')->nullable()->comment('价格');
            $table->unsignedTinyInteger('enabled')->comment('是否启用');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `activity_wholesale` COMMENT '批发活动表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_wholesale');
    }
};
