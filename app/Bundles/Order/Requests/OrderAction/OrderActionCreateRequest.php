<?php

declare(strict_types=1);

namespace App\Bundles\Order\Requests\OrderAction;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderActionCreateRequest',
    required: [
        self::getActionId,
        self::getOrderId,
        self::getActionUser,
        self::getOrderStatus,
        self::getShippingStatus,
        self::getPayStatus,
        self::getActionPlace,
        self::getActionNote,
        self::getLogTime,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getActionId, description: '', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getActionUser, description: '操作用户', type: 'string'),
        new OA\Property(property: self::getOrderStatus, description: '订单状态', type: 'integer'),
        new OA\Property(property: self::getShippingStatus, description: '配送状态', type: 'integer'),
        new OA\Property(property: self::getPayStatus, description: '支付状态', type: 'integer'),
        new OA\Property(property: self::getActionPlace, description: '操作位置', type: 'integer'),
        new OA\Property(property: self::getActionNote, description: '操作备注', type: 'string'),
        new OA\Property(property: self::getLogTime, description: '日志时间', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class OrderActionCreateRequest extends FormRequest
{
    const string getActionId = 'actionId';

    const string getOrderId = 'orderId';

    const string getActionUser = 'actionUser';

    const string getOrderStatus = 'orderStatus';

    const string getShippingStatus = 'shippingStatus';

    const string getPayStatus = 'payStatus';

    const string getActionPlace = 'actionPlace';

    const string getActionNote = 'actionNote';

    const string getLogTime = 'logTime';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getActionId => 'required',
            self::getOrderId => 'required',
            self::getActionUser => 'required',
            self::getOrderStatus => 'required',
            self::getShippingStatus => 'required',
            self::getPayStatus => 'required',
            self::getActionPlace => 'required',
            self::getActionNote => 'required',
            self::getLogTime => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getActionId.'.required' => '请设置',
            self::getOrderId.'.required' => '请设置订单ID',
            self::getActionUser.'.required' => '请设置操作用户',
            self::getOrderStatus.'.required' => '请设置订单状态',
            self::getShippingStatus.'.required' => '请设置配送状态',
            self::getPayStatus.'.required' => '请设置支付状态',
            self::getActionPlace.'.required' => '请设置操作位置',
            self::getActionNote.'.required' => '请设置操作备注',
            self::getLogTime.'.required' => '请设置日志时间',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
