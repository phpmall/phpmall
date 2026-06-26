<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CartStoreRequest',
    required: [
        self::getSkuId,
        self::getQuantity,
    ],
    properties: [
        new OA\Property(property: self::getSkuId, description: 'SKU ID', type: 'integer'),
        new OA\Property(property: self::getQuantity, description: '购买数量', type: 'integer', minimum: 1),
    ]
)]
class CartStoreRequest extends FormRequest
{
    const string getSkuId = 'sku_id';

    const string getQuantity = 'quantity';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getSkuId => ['required', 'integer', 'min:1'],
            self::getQuantity => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getSkuId.'.required' => '请选择商品规格',
            self::getQuantity.'.required' => '请输入购买数量',
        ];
    }
}
