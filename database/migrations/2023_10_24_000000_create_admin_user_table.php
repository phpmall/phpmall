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
        Schema::create('admin_user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_name')->default('')->unique()->comment('用户名');
            $table->string('email')->default('')->comment('邮箱');
            $table->string('password')->default('')->comment('密码');
            $table->string('ec_salt')->nullable()->comment('EC盐值');
            $table->unsignedInteger('add_time')->default(0)->comment('添加时间');
            $table->unsignedInteger('last_login')->default(0)->comment('最后登录时间');
            $table->string('last_ip')->default('')->comment('最后登录IP');
            $table->text('action_list')->nullable()->comment('操作列表');
            $table->text('nav_list')->nullable()->comment('导航列表');
            $table->string('lang_type')->default('')->comment('语言类型');
            $table->unsignedInteger('agency_id')->nullable()->index()->comment('办事处ID');
            $table->unsignedInteger('suppliers_id')->nullable()->default(0)->comment('供应商ID');
            $table->text('todolist')->nullable()->comment('待办事项');
            $table->unsignedInteger('role_id')->nullable()->comment('角色ID');
            $table->rememberToken();
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');
        });

        DB::statement("ALTER TABLE `admin_user` COMMENT '管理员用户表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_user');
    }
};
