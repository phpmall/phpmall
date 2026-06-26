<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\ProductSku;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerProductSkuBatchUpdateRequest',
    required: [
        self::getItems,
    ],
    properties: [
        new OA\Property(
            property: self::getItems,
            description: 'SKU批量更新列表',
            type: 'array',
            items: new OA\Items(type: 'object', properties: [
                new OA\Property(property: 'id', type: 'integer', description: 'SKU ID'),
                new OA\Property(property: 'sku_code', type: 'string', description: 'SKU编码'),
                new OA\Property(property: 'price', type: 'integer', description: '售价(分)'),
                new OA\Property(property: 'stock', type: 'integer', description: '库存数量'),
                new OA\Property(property: 'image', type: 'string', description: 'SKU图片', nullable: true),
            ])
        ),
    ]
)]
class ProductSkuBatchUpdateRequest extends FormRequest
{
    const string getItems = 'items';

    public function rules(): array
    {
        return [
            self::getItems => 'required|array',
            self::getItems.'.*.id' => 'required|integer|min:1',
            self::getItems.'.*.sku_code' => 'required|string|max:100',
            self::getItems.'.*.price' => 'required|integer|min:0',
            self::getItems.'.*.stock' => 'required|integer|min:0',
            self::getItems.'.*.image' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            self::getItems.'.required' => '请提供SKU数据',
            self::getItems.'.array' => 'SKU数据格式不正确',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
