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
        Schema::create('shop_friend_link', function (Blueprint $table) {
            $table->increments('link_id');
            $table->string('link_name')->default('')->comment('链接名称');
            $table->string('link_url')->default('')->comment('链接地址');
            $table->string('link_logo')->default('')->comment('链接Logo');
            $table->unsignedTinyInteger('show_order')->default(50)->index()->comment('排序');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `shop_friend_link` COMMENT '友情链接表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_friend_link');
    }
};
