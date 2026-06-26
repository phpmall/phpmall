<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Shipment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerShipmentBatchShipRequest',
    required: [
        self::getShipments,
    ],
    properties: [
        new OA\Property(property: self::getShipments, description: '批量发货数据', type: 'array', items: new OA\Items(type: 'object', properties: [
            new OA\Property(property: 'order_id', description: '订单ID', type: 'integer'),
            new OA\Property(property: 'logistics_company', description: '物流公司', type: 'string'),
            new OA\Property(property: 'tracking_no', description: '物流单号', type: 'string'),
            new OA\Property(property: 'items', description: '发货商品项', type: 'array', items: new OA\Items(type: 'object', properties: [
                new OA\Property(property: 'item_id', description: '订单商品项ID', type: 'integer'),
                new OA\Property(property: 'quantity', description: '发货数量', type: 'integer'),
            ])),
            new OA\Property(property: 'remark', description: '发货备注', type: 'string', nullable: true),
        ])),
    ]
)]
class ShipmentBatchShipRequest extends FormRequest
{
    const string getShipments = 'shipments';

    public function rules(): array
    {
        return [
            self::getShipments => 'required|array|min:1',
            self::getShipments.'.*.order_id' => 'required|integer|min:1',
            self::getShipments.'.*.logistics_company' => 'required|string|max:100',
            self::getShipments.'.*.tracking_no' => 'required|string|max:100',
            self::getShipments.'.*.items' => 'nullable|array',
            self::getShipments.'.*.items.*.item_id' => 'required_with:'.self::getShipments.'.*.items|integer|min:1',
            self::getShipments.'.*.items.*.quantity' => 'required_with:'.self::getShipments.'.*.items|integer|min:1',
            self::getShipments.'.*.remark' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            self::getShipments.'.required' => '请填写发货数据',
            self::getShipments.'.min' => '至少填写一条发货数据',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
