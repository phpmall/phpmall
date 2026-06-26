<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerProductStoreRequest',
    required: [
        self::getName,
        self::getCategoryId,
        self::getPrice,
        self::getStock,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '商品名称', type: 'string', maxLength: 255),
        new OA\Property(property: self::getDescription, description: '商品描述', type: 'string', nullable: true),
        new OA\Property(property: self::getCategoryId, description: '商品分类ID', type: 'integer'),
        new OA\Property(property: self::getBrandId, description: '品牌ID', type: 'integer', nullable: true),
        new OA\Property(property: self::getShopCategoryId, description: '店铺分类ID', type: 'integer', nullable: true),
        new OA\Property(property: self::getPrice, description: '销售价(分)', type: 'integer'),
        new OA\Property(property: self::getMarketPrice, description: '市场价(分)', type: 'integer', nullable: true),
        new OA\Property(property: self::getCostPrice, description: '成本价(分)', type: 'integer', nullable: true),
        new OA\Property(property: self::getStock, description: '库存数量', type: 'integer'),
        new OA\Property(property: self::getImages, description: '商品图片列表', type: 'array', items: new OA\Items(type: 'string'), nullable: true),
        new OA\Property(property: self::getAttributes, description: '商品属性列表', type: 'array', items: new OA\Items(type: 'object'), nullable: true),
        new OA\Property(property: self::getSkus, description: 'SKU列表', type: 'array', items: new OA\Items(type: 'object'), nullable: true),
    ]
)]
class ProductStoreRequest extends FormRequest
{
    const string getName = 'name';

    const string getDescription = 'description';

    const string getCategoryId = 'category_id';

    const string getBrandId = 'brand_id';

    const string getShopCategoryId = 'shop_category_id';

    const string getPrice = 'price';

    const string getMarketPrice = 'market_price';

    const string getCostPrice = 'cost_price';

    const string getStock = 'stock';

    const string getImages = 'images';

    const string getAttributes = 'attributes';

    const string getSkus = 'skus';

    public function rules(): array
    {
        return [
            self::getName => 'required|string|max:255',
            self::getDescription => 'nullable|string',
            self::getCategoryId => 'required|integer|min:1',
            self::getBrandId => 'nullable|integer|min:1',
            self::getShopCategoryId => 'nullable|integer|min:1',
            self::getPrice => 'required|integer|min:0',
            self::getMarketPrice => 'nullable|integer|min:0',
            self::getCostPrice => 'nullable|integer|min:0',
            self::getStock => 'required|integer|min:0',
            self::getImages => 'nullable|array',
            self::getImages.'.*' => 'string',
            self::getAttributes => 'nullable|array',
            self::getSkus => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请填写商品名称',
            self::getName.'.max' => '商品名称不能超过255个字符',
            self::getCategoryId.'.required' => '请选择商品分类',
            self::getPrice.'.required' => '请填写销售价',
            self::getPrice.'.min' => '销售价不能小于0',
            self::getStock.'.required' => '请填写库存数量',
            self::getStock.'.min' => '库存数量不能小于0',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
