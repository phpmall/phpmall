<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserBonus;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserBonusUpdateRequest',
    required: [
        self::getBonusId,
        self::getBonusTypeId,
        self::getBonusSn,
        self::getUserId,
        self::getUsedTime,
        self::getOrderId,
        self::getEmailed,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getBonusId, description: '', type: 'integer'),
        new OA\Property(property: self::getBonusTypeId, description: '红包类型ID', type: 'integer'),
        new OA\Property(property: self::getBonusSn, description: '红包序列号', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getUsedTime, description: '使用时间', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getEmailed, description: '是否已发送邮件', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class UserBonusUpdateRequest extends FormRequest
{
    const string getBonusId = 'bonusId';

    const string getBonusTypeId = 'bonusTypeId';

    const string getBonusSn = 'bonusSn';

    const string getUserId = 'userId';

    const string getUsedTime = 'usedTime';

    const string getOrderId = 'orderId';

    const string getEmailed = 'emailed';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getBonusId => 'required',
            self::getBonusTypeId => 'required',
            self::getBonusSn => 'required',
            self::getUserId => 'required',
            self::getUsedTime => 'required',
            self::getOrderId => 'required',
            self::getEmailed => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getBonusId.'.required' => '请设置',
            self::getBonusTypeId.'.required' => '请设置红包类型ID',
            self::getBonusSn.'.required' => '请设置红包序列号',
            self::getUserId.'.required' => '请设置用户ID',
            self::getUsedTime.'.required' => '请设置使用时间',
            self::getOrderId.'.required' => '请设置订单ID',
            self::getEmailed.'.required' => '请设置是否已发送邮件',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
