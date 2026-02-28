<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserAffiliate;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserAffiliateCreateRequest',
    required: [
        self::getLogId,
        self::getOrderId,
        self::getTime,
        self::getUserId,
        self::getUserName,
        self::getMoney,
        self::getPoint,
        self::getSeparateType,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getLogId, description: '', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getTime, description: '时间', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getUserName, description: '用户名', type: 'string'),
        new OA\Property(property: self::getMoney, description: '金额', type: 'string'),
        new OA\Property(property: self::getPoint, description: '积分', type: 'integer'),
        new OA\Property(property: self::getSeparateType, description: '分成类型', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class UserAffiliateCreateRequest extends FormRequest
{
    const string getLogId = 'logId';

    const string getOrderId = 'orderId';

    const string getTime = 'time';

    const string getUserId = 'userId';

    const string getUserName = 'userName';

    const string getMoney = 'money';

    const string getPoint = 'point';

    const string getSeparateType = 'separateType';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getLogId => 'required',
            self::getOrderId => 'required',
            self::getTime => 'required',
            self::getUserId => 'required',
            self::getUserName => 'required',
            self::getMoney => 'required',
            self::getPoint => 'required',
            self::getSeparateType => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getLogId.'.required' => '请设置',
            self::getOrderId.'.required' => '请设置订单ID',
            self::getTime.'.required' => '请设置时间',
            self::getUserId.'.required' => '请设置用户ID',
            self::getUserName.'.required' => '请设置用户名',
            self::getMoney.'.required' => '请设置金额',
            self::getPoint.'.required' => '请设置积分',
            self::getSeparateType.'.required' => '请设置分成类型',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
