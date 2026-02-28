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
        Schema::create('shop_card', function (Blueprint $table) {
            $table->tinyIncrements('card_id');
            $table->string('card_name')->default('')->comment('贺卡名称');
            $table->string('card_img')->default('')->comment('贺卡图片');
            $table->decimal('card_fee')->unsigned()->default(0)->comment('贺卡费用');
            $table->decimal('free_money')->unsigned()->default(0)->comment('免费额度');
            $table->string('card_desc')->default('')->comment('贺卡描述');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `shop_card` COMMENT '贺卡表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_card');
    }
};
