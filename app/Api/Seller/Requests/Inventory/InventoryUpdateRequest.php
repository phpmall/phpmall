<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerInventoryUpdateRequest',
    required: [
        self::getQuantity,
    ],
    properties: [
        new OA\Property(property: self::getQuantity, description: '库存数量', type: 'integer'),
        new OA\Property(property: self::getAlertThreshold, description: '库存预警阈值', type: 'integer', nullable: true),
    ]
)]
class InventoryUpdateRequest extends FormRequest
{
    const string getQuantity = 'quantity';

    const string getAlertThreshold = 'alert_threshold';

    public function rules(): array
    {
        return [
            self::getQuantity => ['required', 'integer', 'min:0'],
            self::getAlertThreshold => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getQuantity.'.required' => '请填写库存数量',
            self::getQuantity.'.integer' => '库存数量必须为整数',
            self::getQuantity.'.min' => '库存数量不能小于0',
            self::getAlertThreshold.'.integer' => '预警阈值必须为整数',
            self::getAlertThreshold.'.min' => '预警阈值不能小于0',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
