<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Shipment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerShipmentStoreRequest',
    required: [
        self::getOrderId,
        self::getLogisticsCompany,
        self::getTrackingNo,
    ],
    properties: [
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getLogisticsCompany, description: '物流公司', type: 'string'),
        new OA\Property(property: self::getTrackingNo, description: '物流单号', type: 'string'),
        new OA\Property(property: self::getItems, description: '发货商品项', type: 'array', items: new OA\Items(type: 'object', properties: [
            new OA\Property(property: 'item_id', description: '订单商品项ID', type: 'integer'),
            new OA\Property(property: 'quantity', description: '发货数量', type: 'integer'),
        ])),
        new OA\Property(property: self::getRemark, description: '发货备注', type: 'string', nullable: true),
    ]
)]
class ShipmentStoreRequest extends FormRequest
{
    const string getOrderId = 'order_id';

    const string getLogisticsCompany = 'logistics_company';

    const string getTrackingNo = 'tracking_no';

    const string getItems = 'items';

    const string getRemark = 'remark';

    public function rules(): array
    {
        return [
            self::getOrderId => 'required|integer|min:1',
            self::getLogisticsCompany => 'required|string|max:100',
            self::getTrackingNo => 'required|string|max:100',
            self::getItems => 'nullable|array',
            self::getItems.'.*.item_id' => 'required_with:'.self::getItems.'|integer|min:1',
            self::getItems.'.*.quantity' => 'required_with:'.self::getItems.'|integer|min:1',
            self::getRemark => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            self::getOrderId.'.required' => '请选择订单',
            self::getLogisticsCompany.'.required' => '请选择物流公司',
            self::getTrackingNo.'.required' => '请填写物流单号',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
