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
        Schema::create('shipping_area', function (Blueprint $table) {
            $table->increments('shipping_area_id');
            $table->string('shipping_area_name')->default('')->comment('配送区域名称');
            $table->unsignedTinyInteger('shipping_id')->default(0)->index()->comment('配送方式ID');
            $table->text('configure')->nullable()->comment('配置信息');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `shipping_area` COMMENT '配送区域表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_area');
    }
};
