<?php

declare(strict_types=1);

namespace App\Api\Portal\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalSelectCartItemsRequest',
    title: '切换购物车勾选状态参数',
    required: ['is_selected'],
    properties: [
        new OA\Property(property: 'cart_ids', description: '购物车条目ID数组（若空则代表全选/全不选）', type: 'array', items: new OA\Items(type: 'integer'), nullable: true, example: [1, 2]),
        new OA\Property(property: 'is_selected', description: '是否勾选：true/false', type: 'boolean', example: true),
    ]
)]
class SelectCartItemsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'cart_ids' => ['nullable', 'array'],
            'cart_ids.*' => ['integer'],
            'is_selected' => ['required', 'boolean'],
        ];
    }
}
