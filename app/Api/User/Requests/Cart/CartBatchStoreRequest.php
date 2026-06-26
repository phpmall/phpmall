<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CartBatchStoreRequest',
    required: [
        self::getItems,
    ],
    properties: [
        new OA\Property(
            property: self::getItems,
            description: '批量添加购物车商品',
            type: 'array',
            items: new OA\Items(type: 'object', properties: [
                new OA\Property(property: 'sku_id', description: 'SKU ID', type: 'integer'),
                new OA\Property(property: 'quantity', description: '购买数量', type: 'integer', minimum: 1),
            ])
        ),
    ]
)]
class CartBatchStoreRequest extends FormRequest
{
    const string getItems = 'items';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getItems => ['required', 'array', 'min:1'],
            self::getItems.'.*.sku_id' => ['required', 'integer', 'min:1'],
            self::getItems.'.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getItems.'.required' => '请添加商品',
            self::getItems.'.min' => '至少添加一个商品',
        ];
    }
}
