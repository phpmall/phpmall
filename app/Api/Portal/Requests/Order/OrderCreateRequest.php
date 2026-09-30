<?php

declare(strict_types=1);

namespace App\Api\Portal\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalOrderCreateRequest',
    title: '创建订单请求参数',
    required: ['items'],
    properties: [
        new OA\Property(
            property: 'items',
            description: '购买商品明细',
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'sku_id', type: 'integer', example: 2001),
                    new OA\Property(property: 'quantity', type: 'integer', example: 1),
                ]
            )
        ),
        new OA\Property(property: 'address_id', description: '收货地址ID', type: 'integer', nullable: true, example: 101),
        new OA\Property(property: 'remark', description: '买家留言', type: 'string', nullable: true, example: '请尽快发货'),
        new OA\Property(property: 'source', description: '订单来源：1-PC，2-H5，3-小程序，4-App', type: 'integer', default: 1, example: 1),
    ]
)]
class OrderCreateRequest extends FormRequest
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
            'address_id' => ['nullable', 'integer'],
            'remark' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'integer', 'in:1,2,3,4'],
        ];
    }
}
