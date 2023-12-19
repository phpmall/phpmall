<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id')->comment('卖家id');
            $table->unsignedBigInteger('shop_id')->comment('店铺id');
            $table->unsignedBigInteger('category_id')->comment('分类id');
            $table->string('category_name')->comment('分类名称');
            $table->unsignedBigInteger('brand_id')->comment('品牌id');
            $table->string('brand_name')->comment('品牌名称');
            $table->unsignedBigInteger('freight_template_id')->comment('运费模版id');
            $table->unsignedBigInteger('product_type_id')->comment('商品类型id');
            $table->string('product_sn')->nullable(false)->comment('货号');
            $table->string('name')->nullable(false)->comment('商品名称');
            $table->string('pic')->comment('图片');
            $table->decimal('original_price', 2)->comment('市场价');
            $table->decimal('price', 2)->comment('价格');
            $table->unsignedInteger('promotion_type')->comment('促销类型：0->没有促销使用原价;1->使用促销价；2->使用会员价；3->使用阶梯价格；4->使用满减价格；5->限时购');
            $table->decimal('promotion_price', 2)->comment('促销价格');
            $table->datetime('promotion_start_time')->comment('促销开始时间');
            $table->datetime('promotion_end_time')->comment('促销结束时间');
            $table->unsignedInteger('promotion_per_limit')->comment('活动限购数量');
            $table->unsignedInteger('gift_growth')->default(0)->comment('赠送的成长值');
            $table->unsignedInteger('gift_point')->default(0)->comment('赠送的积分');
            $table->unsignedInteger('use_point_limit')->comment('限制使用的积分数');
            $table->unsignedInteger('sale')->comment('销量');
            $table->unsignedInteger('stock')->comment('库存');
            $table->unsignedInteger('low_stock')->comment('库存预警值');
            $table->string('unit')->comment('单位');
            $table->decimal('weight', 2)->comment('商品重量，默认为克');
            $table->unsignedInteger('preview_status')->comment('是否为预告商品：0->不是；1->是');
            $table->string('service_ids')->comment('以逗号分割的产品服务：1->无忧退货；2->快速退款；3->免费包邮');
            $table->string('sub_title')->comment('副标题');
            $table->text('description')->comment('商品描述');
            $table->string('keywords')->comment('关键字');
            $table->string('note')->comment('备注');
            $table->string('album_pics')->comment('画册图片，连产品图片限制为5张，以逗号分割');
            $table->string('detail_title')->comment('详情标题');
            $table->text('detail_desc')->comment('详情描述');
            $table->text('detail_html')->comment('产品详情网页内容');
            $table->text('detail_mobile_html')->comment('移动端网页详情');
            $table->unsignedInteger('delete_status')->comment('删除状态：0->未删除；1->已删除');
            $table->unsignedInteger('publish_status')->comment('上架状态：0->下架；1->上架');
            $table->unsignedInteger('new_status')->comment('新品状态:0->不是新品；1->新品');
            $table->unsignedInteger('recommend_status')->comment('推荐状态；0->不推荐；1->推荐');
            $table->unsignedInteger('verify_status')->comment('审核状态：0->未审核；1->审核通过');
            $table->unsignedInteger('sort')->comment('排序');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('商品表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
