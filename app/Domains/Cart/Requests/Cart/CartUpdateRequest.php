<?php

declare(strict_types=1);

namespace App\Domains\Cart\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CartUpdateRequest',
    required: [
        self::getId,
        self::getUserId,
        self::getMerchantId,
        self::getProductId,
        self::getSkuId,
        self::getQuantity,
        self::getIsSelected,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getMerchantId, description: '商家ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getSkuId, description: 'SKU ID', type: 'integer'),
        new OA\Property(property: self::getQuantity, description: '数量', type: 'integer'),
        new OA\Property(property: self::getIsSelected, description: '是否选中', type: 'integer'),
    ]
)]
class CartUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getMerchantId = 'merchantId';

    public const string getProductId = 'productId';

    public const string getSkuId = 'skuId';

    public const string getQuantity = 'quantity';

    public const string getIsSelected = 'isSelected';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getUserId => 'required',
            self::getMerchantId => 'required',
            self::getProductId => 'required',
            self::getSkuId => 'required',
            self::getQuantity => 'required',
            self::getIsSelected => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getUserId.'.required' => '请设置用户ID',
            self::getMerchantId.'.required' => '请设置商家ID',
            self::getProductId.'.required' => '请设置商品ID',
            self::getSkuId.'.required' => '请设置SKU ID',
            self::getQuantity.'.required' => '请设置数量',
            self::getIsSelected.'.required' => '请设置是否选中',
        ];
    }
}
