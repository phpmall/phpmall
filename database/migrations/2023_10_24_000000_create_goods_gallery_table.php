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
        Schema::create('goods_gallery', function (Blueprint $table) {
            $table->increments('img_id');
            $table->unsignedInteger('goods_id')->default(0)->index()->comment('商品ID');
            $table->string('img_url')->default('')->comment('图片URL');
            $table->string('img_desc')->default('')->comment('图片描述');
            $table->string('thumb_url')->default('')->comment('缩略图URL');
            $table->string('img_original')->default('')->comment('原始图片');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `goods_gallery` COMMENT '商品相册表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_gallery');
    }
};
