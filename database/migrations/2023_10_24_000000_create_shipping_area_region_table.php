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
        Schema::create('shipping_area_region', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shipping_area_id')->default(0)->comment('配送区域ID');
            $table->unsignedInteger('region_id')->default(0)->comment('地区ID');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');

            $table->unique(['shipping_area_id', 'region_id'], 'shipping_area_id_region_id');
        });

        DB::statement("ALTER TABLE `shipping_area_region` COMMENT '配送区域地区表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_area_region');
    }
};
