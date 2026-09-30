<?php

declare(strict_types=1);

namespace App\Api\Portal\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalOrderPreviewRequest',
    title: '订单结算预览请求参数',
    required: ['items'],
    properties: [
        new OA\Property(
            property: 'items',
            description: '结算商品列表',
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'sku_id', type: 'integer', example: 2001),
                    new OA\Property(property: 'quantity', type: 'integer', example: 1),
                ]
            )
        ),
    ]
)]
class OrderPreviewRequest extends FormRequest
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
            'items' => ['required', 'array', 'min:1'],
            'items.*.sku_id' => ['required', 'integer', 'min:1'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:999'],
        ];
    }
}
