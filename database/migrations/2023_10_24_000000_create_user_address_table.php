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
        Schema::create('user_address', function (Blueprint $table) {
            $table->increments('address_id');
            $table->string('address_name')->default('')->comment('地址名称');
            $table->unsignedInteger('user_id')->default(0)->index()->comment('用户ID');
            $table->string('consignee')->default('')->comment('收货人');
            $table->string('email')->default('')->comment('邮箱');
            $table->integer('country')->default(0)->comment('国家');
            $table->integer('province')->default(0)->comment('省份');
            $table->integer('city')->default(0)->comment('城市');
            $table->integer('district')->default(0)->comment('区县');
            $table->string('address')->default('')->comment('详细地址');
            $table->string('zipcode')->default('')->comment('邮编');
            $table->string('tel')->default('')->comment('电话');
            $table->string('mobile')->default('')->comment('手机');
            $table->string('sign_building')->default('')->comment('标志建筑');
            $table->string('best_time')->default('')->comment('最佳送货时间');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `user_address` COMMENT '用户地址表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_address');
    }
};
