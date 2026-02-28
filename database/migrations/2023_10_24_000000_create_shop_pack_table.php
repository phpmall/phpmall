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
        Schema::create('shop_pack', function (Blueprint $table) {
            $table->tinyIncrements('pack_id');
            $table->string('pack_name')->default('')->comment('包装名称');
            $table->string('pack_img')->default('')->comment('包装图片');
            $table->decimal('pack_fee')->unsigned()->default(0)->comment('包装费用');
            $table->unsignedInteger('free_money')->default(0)->comment('免费额度');
            $table->string('pack_desc')->default('')->comment('包装描述');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `shop_pack` COMMENT '包装表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_pack');
    }
};
