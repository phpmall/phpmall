<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Requests\Activity;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ActivityCreateRequest',
    required: [
        self::getActId,
        self::getActName,
        self::getStartTime,
        self::getEndTime,
        self::getUserRank,
        self::getActRange,
        self::getActRangeExt,
        self::getMinAmount,
        self::getMaxAmount,
        self::getActType,
        self::getActTypeExt,
        self::getGift,
        self::getSortOrder,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getActId, description: '', type: 'integer'),
        new OA\Property(property: self::getActName, description: '活动名称', type: 'string'),
        new OA\Property(property: self::getStartTime, description: '开始时间', type: 'integer'),
        new OA\Property(property: self::getEndTime, description: '结束时间', type: 'integer'),
        new OA\Property(property: self::getUserRank, description: '用户等级', type: 'string'),
        new OA\Property(property: self::getActRange, description: '活动范围', type: 'integer'),
        new OA\Property(property: self::getActRangeExt, description: '活动范围扩展', type: 'string'),
        new OA\Property(property: self::getMinAmount, description: '最小金额', type: 'string'),
        new OA\Property(property: self::getMaxAmount, description: '最大金额', type: 'string'),
        new OA\Property(property: self::getActType, description: '活动类型', type: 'integer'),
        new OA\Property(property: self::getActTypeExt, description: '活动类型扩展', type: 'string'),
        new OA\Property(property: self::getGift, description: '赠品', type: 'string'),
        new OA\Property(property: self::getSortOrder, description: '排序顺序', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ActivityCreateRequest extends FormRequest
{
    const string getActId = 'actId';

    const string getActName = 'actName';

    const string getStartTime = 'startTime';

    const string getEndTime = 'endTime';

    const string getUserRank = 'userRank';

    const string getActRange = 'actRange';

    const string getActRangeExt = 'actRangeExt';

    const string getMinAmount = 'minAmount';

    const string getMaxAmount = 'maxAmount';

    const string getActType = 'actType';

    const string getActTypeExt = 'actTypeExt';

    const string getGift = 'gift';

    const string getSortOrder = 'sortOrder';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getActId => 'required',
            self::getActName => 'required',
            self::getStartTime => 'required',
            self::getEndTime => 'required',
            self::getUserRank => 'required',
            self::getActRange => 'required',
            self::getActRangeExt => 'required',
            self::getMinAmount => 'required',
            self::getMaxAmount => 'required',
            self::getActType => 'required',
            self::getActTypeExt => 'required',
            self::getGift => 'required',
            self::getSortOrder => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getActId.'.required' => '请设置',
            self::getActName.'.required' => '请设置活动名称',
            self::getStartTime.'.required' => '请设置开始时间',
            self::getEndTime.'.required' => '请设置结束时间',
            self::getUserRank.'.required' => '请设置用户等级',
            self::getActRange.'.required' => '请设置活动范围',
            self::getActRangeExt.'.required' => '请设置活动范围扩展',
            self::getMinAmount.'.required' => '请设置最小金额',
            self::getMaxAmount.'.required' => '请设置最大金额',
            self::getActType.'.required' => '请设置活动类型',
            self::getActTypeExt.'.required' => '请设置活动类型扩展',
            self::getGift.'.required' => '请设置赠品',
            self::getSortOrder.'.required' => '请设置排序顺序',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
