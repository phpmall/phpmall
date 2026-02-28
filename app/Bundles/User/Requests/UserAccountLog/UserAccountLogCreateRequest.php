<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserAccountLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserAccountLogCreateRequest',
    required: [
        self::getLogId,
        self::getUserId,
        self::getUserMoney,
        self::getFrozenMoney,
        self::getRankPoints,
        self::getPayPoints,
        self::getChangeTime,
        self::getChangeDesc,
        self::getChangeType,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getLogId, description: '', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getUserMoney, description: '用户余额', type: 'string'),
        new OA\Property(property: self::getFrozenMoney, description: '冻结金额', type: 'string'),
        new OA\Property(property: self::getRankPoints, description: '等级积分', type: 'integer'),
        new OA\Property(property: self::getPayPoints, description: '消费积分', type: 'integer'),
        new OA\Property(property: self::getChangeTime, description: '变更时间', type: 'integer'),
        new OA\Property(property: self::getChangeDesc, description: '变更描述', type: 'string'),
        new OA\Property(property: self::getChangeType, description: '变更类型', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class UserAccountLogCreateRequest extends FormRequest
{
    const string getLogId = 'logId';

    const string getUserId = 'userId';

    const string getUserMoney = 'userMoney';

    const string getFrozenMoney = 'frozenMoney';

    const string getRankPoints = 'rankPoints';

    const string getPayPoints = 'payPoints';

    const string getChangeTime = 'changeTime';

    const string getChangeDesc = 'changeDesc';

    const string getChangeType = 'changeType';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getLogId => 'required',
            self::getUserId => 'required',
            self::getUserMoney => 'required',
            self::getFrozenMoney => 'required',
            self::getRankPoints => 'required',
            self::getPayPoints => 'required',
            self::getChangeTime => 'required',
            self::getChangeDesc => 'required',
            self::getChangeType => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getLogId.'.required' => '请设置',
            self::getUserId.'.required' => '请设置用户ID',
            self::getUserMoney.'.required' => '请设置用户余额',
            self::getFrozenMoney.'.required' => '请设置冻结金额',
            self::getRankPoints.'.required' => '请设置等级积分',
            self::getPayPoints.'.required' => '请设置消费积分',
            self::getChangeTime.'.required' => '请设置变更时间',
            self::getChangeDesc.'.required' => '请设置变更描述',
            self::getChangeType.'.required' => '请设置变更类型',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
