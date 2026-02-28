<?php

declare(strict_types=1);

namespace App\Bundles\Order\Requests\OrderPay;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderPayCreateRequest',
    required: [
        self::getLogId,
        self::getOrderId,
        self::getOrderAmount,
        self::getOrderType,
        self::getIsPaid,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getLogId, description: '', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getOrderAmount, description: '订单金额', type: 'string'),
        new OA\Property(property: self::getOrderType, description: '订单类型', type: 'integer'),
        new OA\Property(property: self::getIsPaid, description: '是否已支付', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class OrderPayCreateRequest extends FormRequest
{
    const string getLogId = 'logId';

    const string getOrderId = 'orderId';

    const string getOrderAmount = 'orderAmount';

    const string getOrderType = 'orderType';

    const string getIsPaid = 'isPaid';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getLogId => 'required',
            self::getOrderId => 'required',
            self::getOrderAmount => 'required',
            self::getOrderType => 'required',
            self::getIsPaid => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getLogId.'.required' => '请设置',
            self::getOrderId.'.required' => '请设置订单ID',
            self::getOrderAmount.'.required' => '请设置订单金额',
            self::getOrderType.'.required' => '请设置订单类型',
            self::getIsPaid.'.required' => '请设置是否已支付',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
