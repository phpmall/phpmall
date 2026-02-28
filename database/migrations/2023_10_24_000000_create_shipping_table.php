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
        Schema::create('shipping', function (Blueprint $table) {
            $table->tinyIncrements('shipping_id');
            $table->string('shipping_code')->default('')->comment('配送代码');
            $table->string('shipping_name')->default('')->comment('配送名称');
            $table->string('shipping_desc')->default('')->comment('配送描述');
            $table->string('insure')->default('0')->comment('保价');
            $table->unsignedTinyInteger('support_cod')->default(0)->comment('是否支持货到付款');
            $table->unsignedTinyInteger('enabled')->default(0)->comment('是否启用');
            $table->text('shipping_print')->nullable()->comment('打印模板');
            $table->string('print_bg')->nullable()->comment('打印背景');
            $table->text('config_label')->nullable()->comment('配置标签');
            $table->boolean('print_model')->nullable()->default(false)->comment('打印模式');
            $table->unsignedTinyInteger('shipping_order')->default(0)->comment('排序');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');

            $table->index(['shipping_code', 'enabled'], 'shipping_code');
        });

        DB::statement("ALTER TABLE `shipping` COMMENT '配送方式表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping');
    }
};
