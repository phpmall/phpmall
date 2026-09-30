<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderShipment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderShipmentUpdateRequest',
    required: [
        self::getId,
        self::getOrderId,
        self::getMerchantId,
        self::getLogisticsCompany,
        self::getTrackingNo,
        self::getRemark,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getMerchantId, description: '商家ID', type: 'integer'),
        new OA\Property(property: self::getLogisticsCompany, description: '物流公司', type: 'string'),
        new OA\Property(property: self::getTrackingNo, description: '物流单号', type: 'string'),
        new OA\Property(property: self::getRemark, description: '发货备注', type: 'string'),
    ]
)]
class OrderShipmentUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getOrderId = 'orderId';

    public const string getMerchantId = 'merchantId';

    public const string getLogisticsCompany = 'logisticsCompany';

    public const string getTrackingNo = 'trackingNo';

    public const string getRemark = 'remark';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getOrderId => 'required',
            self::getMerchantId => 'required',
            self::getLogisticsCompany => 'required',
            self::getTrackingNo => 'required',
            self::getRemark => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getOrderId.'.required' => '请设置订单ID',
            self::getMerchantId.'.required' => '请设置商家ID',
            self::getLogisticsCompany.'.required' => '请设置物流公司',
            self::getTrackingNo.'.required' => '请设置物流单号',
            self::getRemark.'.required' => '请设置发货备注',
        ];
    }
}
