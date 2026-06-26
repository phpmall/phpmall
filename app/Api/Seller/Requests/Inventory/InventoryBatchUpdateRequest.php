<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerInventoryBatchUpdateRequest',
    required: [
        self::getItems,
    ],
    properties: [
        new OA\Property(
            property: self::getItems,
            description: '批量更新项',
            type: 'array',
            items: new OA\Items(properties: [
                new OA\Property(property: 'id', description: '库存ID', type: 'integer'),
                new OA\Property(property: 'quantity', description: '库存数量', type: 'integer'),
                new OA\Property(property: 'alert_threshold', description: '库存预警阈值', type: 'integer', nullable: true),
            ])
        ),
    ]
)]
class InventoryBatchUpdateRequest extends FormRequest
{
    const string getItems = 'items';

    public function rules(): array
    {
        return [
            self::getItems => ['required', 'array'],
            self::getItems.'.*.id' => ['required', 'integer', 'min:1'],
            self::getItems.'.*.quantity' => ['required', 'integer', 'min:0'],
            self::getItems.'.*.alert_threshold' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getItems.'.required' => '请填写更新项',
            self::getItems.'.array' => '更新项必须为数组',
            self::getItems.'.*.id.required' => '库存ID不能为空',
            self::getItems.'.*.id.integer' => '库存ID必须为整数',
            self::getItems.'.*.quantity.required' => '请填写库存数量',
            self::getItems.'.*.quantity.integer' => '库存数量必须为整数',
            self::getItems.'.*.quantity.min' => '库存数量不能小于0',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
