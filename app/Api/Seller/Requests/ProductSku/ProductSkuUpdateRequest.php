<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\ProductSku;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerProductSkuUpdateRequest',
    required: [
        self::getSkuCode,
        self::getPrice,
        self::getStock,
    ],
    properties: [
        new OA\Property(property: self::getSkuCode, description: 'SKU编码', type: 'string', maxLength: 100),
        new OA\Property(property: self::getPrice, description: 'SKU售价(分)', type: 'integer'),
        new OA\Property(property: self::getStock, description: '库存数量', type: 'integer'),
        new OA\Property(
            property: self::getAttributes,
            description: 'SKU属性值',
            type: 'array',
            items: new OA\Items(type: 'object', properties: [
                new OA\Property(property: 'attribute_id', type: 'integer', description: '属性ID'),
                new OA\Property(property: 'value', type: 'string', description: '属性值'),
            ]),
            nullable: true
        ),
        new OA\Property(property: self::getImage, description: 'SKU图片', type: 'string', nullable: true),
    ]
)]
class ProductSkuUpdateRequest extends FormRequest
{
    const string getSkuCode = 'sku_code';

    const string getPrice = 'price';

    const string getStock = 'stock';

    const string getAttributes = 'attributes';

    const string getImage = 'image';

    public function rules(): array
    {
        return [
            self::getSkuCode => 'required|string|max:100',
            self::getPrice => 'required|integer|min:0',
            self::getStock => 'required|integer|min:0',
            self::getAttributes => 'nullable|array',
            self::getImage => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            self::getSkuCode.'.required' => '请填写SKU编码',
            self::getPrice.'.required' => '请填写售价',
            self::getStock.'.required' => '请填写库存数量',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
