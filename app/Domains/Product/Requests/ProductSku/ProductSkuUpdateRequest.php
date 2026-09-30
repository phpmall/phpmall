<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductSku;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductSkuUpdateRequest',
    required: [
        self::getId,
        self::getProductId,
        self::getMerchantId,
        self::getSkuCode,
        self::getSkuSpecs,
        self::getPrice,
        self::getMarketPrice,
        self::getCostPrice,
        self::getStock,
        self::getStockAlarm,
        self::getWeight,
        self::getImage,
        self::getSalesCount,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getMerchantId, description: '商家ID', type: 'integer'),
        new OA\Property(property: self::getSkuCode, description: 'SKU编码', type: 'string'),
        new OA\Property(property: self::getSkuSpecs, description: '规格组合', type: 'string'),
        new OA\Property(property: self::getPrice, description: '售价（分）', type: 'integer'),
        new OA\Property(property: self::getMarketPrice, description: '市场价（分）', type: 'integer'),
        new OA\Property(property: self::getCostPrice, description: '成本价（分）', type: 'integer'),
        new OA\Property(property: self::getStock, description: '库存', type: 'integer'),
        new OA\Property(property: self::getStockAlarm, description: '库存预警值', type: 'integer'),
        new OA\Property(property: self::getWeight, description: '重量（克）', type: 'integer'),
        new OA\Property(property: self::getImage, description: 'SKU独立图片', type: 'string'),
        new OA\Property(property: self::getSalesCount, description: 'SKU销量', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态：0-禁用，1-启用', type: 'integer'),
    ]
)]
class ProductSkuUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getProductId = 'productId';

    public const string getMerchantId = 'merchantId';

    public const string getSkuCode = 'skuCode';

    public const string getSkuSpecs = 'skuSpecs';

    public const string getPrice = 'price';

    public const string getMarketPrice = 'marketPrice';

    public const string getCostPrice = 'costPrice';

    public const string getStock = 'stock';

    public const string getStockAlarm = 'stockAlarm';

    public const string getWeight = 'weight';

    public const string getImage = 'image';

    public const string getSalesCount = 'salesCount';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getProductId => 'required',
            self::getMerchantId => 'required',
            self::getSkuCode => 'required',
            self::getSkuSpecs => 'required',
            self::getPrice => 'required',
            self::getMarketPrice => 'required',
            self::getCostPrice => 'required',
            self::getStock => 'required',
            self::getStockAlarm => 'required',
            self::getWeight => 'required',
            self::getImage => 'required',
            self::getSalesCount => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getProductId.'.required' => '请设置商品ID',
            self::getMerchantId.'.required' => '请设置商家ID',
            self::getSkuCode.'.required' => '请设置SKU编码',
            self::getSkuSpecs.'.required' => '请设置规格组合',
            self::getPrice.'.required' => '请设置售价（分）',
            self::getMarketPrice.'.required' => '请设置市场价（分）',
            self::getCostPrice.'.required' => '请设置成本价（分）',
            self::getStock.'.required' => '请设置库存',
            self::getStockAlarm.'.required' => '请设置库存预警值',
            self::getWeight.'.required' => '请设置重量（克）',
            self::getImage.'.required' => '请设置SKU独立图片',
            self::getSalesCount.'.required' => '请设置SKU销量',
            self::getStatus.'.required' => '请设置状态：0-禁用，1-启用',
        ];
    }
}
