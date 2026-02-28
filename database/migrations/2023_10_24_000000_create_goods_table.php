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
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('goods_id');
            $table->unsignedInteger('cat_id')->default(0)->index()->comment('商品分类ID');
            $table->string('goods_sn')->default('')->unique()->comment('商品编码');
            $table->string('goods_name')->default('')->comment('商品名称');
            $table->string('goods_name_style')->default('+')->comment('商品名称样式');
            $table->unsignedInteger('click_count')->default(0)->comment('点击次数');
            $table->unsignedInteger('brand_id')->default(0)->index()->comment('商品品牌ID');
            $table->string('provider_name')->default('')->comment('供应商名称');
            $table->unsignedInteger('goods_number')->default(0)->index()->comment('商品库存');
            $table->decimal('goods_weight', 10, 3)->unsigned()->default(0)->index()->comment('商品重量');
            $table->decimal('market_price')->unsigned()->default(0)->comment('市场价格');
            $table->decimal('shop_price')->unsigned()->default(0)->comment('商城价格');
            $table->decimal('promote_price')->unsigned()->default(0)->comment('促销价格');
            $table->unsignedInteger('promote_start_date')->default(0)->index()->comment('促销开始时间');
            $table->unsignedInteger('promote_end_date')->default(0)->index()->comment('促销结束时间');
            $table->unsignedTinyInteger('warn_number')->default(1)->comment('库存警告数量');
            $table->string('keywords')->default('')->comment('关键词');
            $table->string('goods_brief')->default('')->comment('商品简介');
            $table->text('goods_desc')->nullable()->comment('商品描述');
            $table->string('goods_thumb')->default('')->comment('商品缩略图');
            $table->string('goods_img')->default('')->comment('商品图片');
            $table->string('original_img')->default('')->comment('商品原图');
            $table->unsignedTinyInteger('is_real')->default(1)->comment('是否实物');
            $table->string('extension_code')->default('')->comment('扩展代码');
            $table->unsignedTinyInteger('is_on_sale')->default(1)->comment('是否上架');
            $table->unsignedTinyInteger('is_alone_sale')->default(1)->comment('是否单独销售');
            $table->unsignedTinyInteger('is_shipping')->default(0)->comment('是否包邮');
            $table->unsignedInteger('integral')->default(0)->comment('积分');
            $table->unsignedInteger('add_time')->default(0)->comment('添加时间');
            $table->unsignedInteger('sort_order')->default(100)->index()->comment('排序');
            $table->unsignedTinyInteger('is_delete')->default(0)->comment('是否删除');
            $table->unsignedTinyInteger('is_best')->default(0)->comment('是否精品');
            $table->unsignedTinyInteger('is_new')->default(0)->comment('是否新品');
            $table->unsignedTinyInteger('is_hot')->default(0)->comment('是否热卖');
            $table->unsignedTinyInteger('is_promote')->default(0)->comment('是否促销');
            $table->unsignedTinyInteger('bonus_type_id')->default(0)->comment('红包类型ID');
            $table->unsignedInteger('last_update')->default(0)->index()->comment('最后更新时间');
            $table->unsignedInteger('goods_type')->default(0)->comment('商品类型');
            $table->string('seller_note')->default('')->comment('商家备注');
            $table->integer('give_integral')->default(-1)->comment('赠送积分');
            $table->integer('rank_integral')->default(-1)->comment('等级积分');
            $table->unsignedInteger('suppliers_id')->nullable()->comment('供应商ID');
            $table->unsignedTinyInteger('is_check')->nullable()->comment('是否审核');
            $table->dateTime('created_time', 3)->useCurrent()->comment('创建时间');
            $table->dateTime('updated_time', 3)->useCurrentOnUpdate()->useCurrent()->comment('更新时间');

            // 复合索引
            $table->index(['cat_id', 'is_on_sale']);
            $table->index(['is_delete', 'is_on_sale', 'cat_id']);
        });

        DB::statement("ALTER TABLE `goods` COMMENT '商品表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods');
    }
};
