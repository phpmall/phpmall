<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerProductBatchDeleteRequest',
    required: [
        self::getIds,
    ],
    properties: [
        new OA\Property(
            property: self::getIds,
            description: '商品ID列表',
            type: 'array',
            items: new OA\Items(type: 'integer')
        ),
    ]
)]
class ProductBatchDeleteRequest extends FormRequest
{
    const string getIds = 'ids';

    public function rules(): array
    {
        return [
            self::getIds => 'required|array',
            self::getIds.'.*' => 'integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            self::getIds.'.required' => '请选择商品',
            self::getIds.'.array' => '商品ID格式不正确',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
