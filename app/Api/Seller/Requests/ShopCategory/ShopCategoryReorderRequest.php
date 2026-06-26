<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\ShopCategory;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerShopCategoryReorderRequest',
    required: [
        self::getIds,
    ],
    properties: [
        new OA\Property(
            property: self::getIds,
            description: '分类ID排序列表',
            type: 'array',
            items: new OA\Items(type: 'integer')
        ),
    ]
)]
class ShopCategoryReorderRequest extends FormRequest
{
    const string getIds = 'ids';

    public function rules(): array
    {
        return [
            self::getIds => ['required', 'array'],
            self::getIds.'.*' => ['integer'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getIds.'.required' => '请提供分类排序列表',
            self::getIds.'.array' => '排序列表格式不正确',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
