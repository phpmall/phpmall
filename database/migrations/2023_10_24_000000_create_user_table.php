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
        Schema::create('user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('email')->default('')->index()->comment('邮箱');
            $table->string('user_name')->default('')->unique()->comment('用户名');
            $table->string('password')->default('')->comment('密码');
            $table->string('question')->default('')->comment('密保问题');
            $table->string('answer')->default('')->comment('密保答案');
            $table->unsignedTinyInteger('sex')->default(0)->comment('性别');
            $table->date('birthday')->nullable()->comment('生日');
            $table->decimal('user_money')->default(0)->comment('用户余额');
            $table->decimal('frozen_money')->default(0)->comment('冻结金额');
            $table->unsignedInteger('pay_points')->default(0)->comment('消费积分');
            $table->unsignedInteger('rank_points')->default(0)->comment('等级积分');
            $table->unsignedInteger('address_id')->default(0)->comment('默认地址ID');
            $table->unsignedInteger('reg_time')->default(0)->comment('注册时间');
            $table->unsignedInteger('last_login')->default(0)->comment('最后登录时间');
            $table->dateTime('last_time')->nullable()->comment('最后访问时间');
            $table->string('last_ip')->default('')->comment('最后登录IP');
            $table->unsignedInteger('visit_count')->default(0)->comment('访问次数');
            $table->unsignedTinyInteger('user_rank')->default(0)->comment('用户等级');
            $table->unsignedTinyInteger('is_special')->default(0)->comment('是否特殊用户');
            $table->string('ec_salt')->nullable()->comment('EC盐值');
            $table->string('salt')->default('0')->comment('密码盐值');
            $table->integer('parent_id')->default(0)->index()->comment('父级ID');
            $table->unsignedTinyInteger('flag')->default(0)->index()->comment('标志');
            $table->string('alias')->nullable()->comment('用户别名');
            $table->string('msn')->nullable()->comment('MSN账号');
            $table->string('qq')->nullable()->comment('QQ号码');
            $table->string('office_phone')->nullable()->comment('办公电话');
            $table->string('home_phone')->nullable()->comment('家庭电话');
            $table->string('mobile_phone')->nullable()->comment('手机号码');
            $table->unsignedTinyInteger('is_validated')->default(0)->comment('是否已验证');
            $table->decimal('credit_line')->nullable()->unsigned()->comment('信用额度');
            $table->string('passwd_question')->nullable()->comment('密码问题');
            $table->string('passwd_answer')->nullable()->comment('密码答案');
            $table->rememberToken();
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `user` COMMENT '用户表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
