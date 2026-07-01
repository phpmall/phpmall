<?php

declare(strict_types=1);

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
        // product_categories
        Schema::create('product_categories', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父分类ID，0=根');
            $table->string('name', 50)->comment('分类名称');
            $table->string('icon_url', 500)->nullable()->comment('图标');
            $table->unsignedInteger('sort_order')->default(0)->comment('排序');
            $table->unsignedTinyInteger('is_show')->default(1)->comment('是否显示 0=否 1=是');
            $table->unsignedTinyInteger('level')->default(1)->comment('层级 1/2/3');
            $table->string('path', 255)->comment('层级路径');
            $table->timestamps();

            $table->index('parent_id', 'idx_product_categories_parent_id');
            $table->index(['is_show', 'sort_order'], 'idx_product_categories_is_show_sort_order');
        });

        // products
        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('merchant_id')->comment('商家ID');
            $table->unsignedBigInteger('category_id')->comment('分类ID');
            $table->string('title', 200)->comment('商品标题');
            $table->string('subtitle', 255)->nullable()->comment('副标题');
            $table->longText('description')->nullable()->comment('富文本详情');
            $table->string('main_image', 500)->comment('主图');
            $table->json('images')->comment('相册');
            $table->unsignedTinyInteger('status')->default(0)->comment('0=下架 1=上架');
            $table->unsignedTinyInteger('audit_status')->default(0)->comment('0=待审核 1=通过 2=拒绝');
            $table->string('audit_remark', 500)->nullable()->comment('审核备注');
            $table->unsignedBigInteger('min_price')->default(0)->comment('最低售价（分）');
            $table->unsignedBigInteger('max_price')->default(0)->comment('最高售价（分）');
            $table->unsignedBigInteger('cost_price')->default(0)->comment('成本价（分）');
            $table->unsignedInteger('sales_count')->default(0)->comment('销量');
            $table->unsignedTinyInteger('stock_type')->default(1)->comment('1=统一库存 2=SKU独立库存');
            $table->unsignedInteger('total_stock')->default(0)->comment('总库存');
            $table->unsignedInteger('weight')->default(0)->comment('重量（克）');
            $table->unsignedBigInteger('freight_template_id')->nullable()->comment('运费模板ID');
            $table->json('attributes')->nullable()->comment('规格属性定义');
            $table->string('seo_title', 200)->nullable()->comment('SEO标题');
            $table->string('seo_keywords', 255)->nullable()->comment('SEO关键词');
            $table->string('seo_description', 500)->nullable()->comment('SEO描述');
            $table->unsignedTinyInteger('is_hot')->default(0)->comment('是否热销');
            $table->unsignedTinyInteger('is_new')->default(0)->comment('是否新品');
            $table->unsignedTinyInteger('is_recommend')->default(0)->comment('是否推荐');
            $table->unsignedInteger('sort_order')->default(0)->comment('排序权重');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['merchant_id', 'status'], 'idx_products_merchant_id_status');
            $table->index(['category_id', 'status', 'audit_status'], 'idx_products_category_id_status_audit_status');
            $table->index('audit_status', 'idx_products_audit_status');
            $table->index(['sort_order', 'created_at'], 'idx_products_sort_order_created_at');
            $table->index('title', 'idx_products_title');
        });

        // product_skus
        Schema::create('product_skus', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('merchant_id')->comment('商家ID');
            $table->string('sku_code', 100)->comment('SKU编码');
            $table->json('sku_specs')->comment('规格组合');
            $table->unsignedBigInteger('price')->comment('售价（分）');
            $table->unsignedBigInteger('market_price')->default(0)->comment('市场价（分）');
            $table->unsignedBigInteger('cost_price')->default(0)->comment('成本价（分）');
            $table->unsignedInteger('stock')->default(0)->comment('库存');
            $table->unsignedInteger('stock_alarm')->default(10)->comment('库存预警值');
            $table->unsignedInteger('weight')->default(0)->comment('重量（克）');
            $table->string('image', 500)->nullable()->comment('SKU独立图片');
            $table->unsignedInteger('sales_count')->default(0)->comment('SKU销量');
            $table->unsignedTinyInteger('status')->default(1)->comment('0=禁用 1=启用');
            $table->timestamps();

            $table->index('product_id', 'idx_product_skus_product_id');
            $table->index('merchant_id', 'idx_product_skus_merchant_id');
            $table->index('status', 'idx_product_skus_status');
        });

        // product_reviews
        Schema::create('product_reviews', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('order_id')->comment('订单ID');
            $table->unsignedBigInteger('order_item_id')->comment('订单商品项ID');
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('sku_id')->comment('SKU ID');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedBigInteger('merchant_id')->comment('商家ID');
            $table->unsignedTinyInteger('rating')->comment('1-5星');
            $table->string('content', 1000)->nullable()->comment('评价内容');
            $table->json('images')->nullable()->comment('评价图片');
            $table->unsignedTinyInteger('is_anonymous')->default(0)->comment('是否匿名');
            $table->unsignedTinyInteger('is_append')->default(0)->comment('是否追评');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('追评时指向原评价');
            $table->string('merchant_reply', 500)->nullable()->comment('商家回复');
            $table->timestamp('merchant_reply_at')->nullable();
            $table->unsignedTinyInteger('status')->default(1)->comment('0=隐藏 1=显示');
            $table->timestamps();
            $table->softDeletes();

            $table->index('product_id', 'idx_product_reviews_product_id');
            $table->index('user_id', 'idx_product_reviews_user_id');
            $table->index('order_item_id', 'idx_product_reviews_order_item_id');
            $table->index('merchant_id', 'idx_product_reviews_merchant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('product_skus');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
    }
};
