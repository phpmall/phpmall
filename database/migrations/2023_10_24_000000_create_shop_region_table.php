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
        Schema::create('shop_region', function (Blueprint $table) {
            $table->increments('region_id');
            $table->boolean('region_type')->default(false)->index()->comment('地区类型');
            $table->unsignedInteger('agency_id')->default(0)->index()->comment('办事处ID');
            $table->unsignedInteger('parent_id')->default(0)->index()->comment('父级ID');
            $table->string('region_name')->default('')->comment('地区名称');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `shop_region` COMMENT '地区表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_region');
    }
};
