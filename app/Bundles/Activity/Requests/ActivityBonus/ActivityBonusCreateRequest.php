<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Requests\ActivityBonus;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ActivityBonusCreateRequest',
    required: [
        self::getTypeId,
        self::getTypeName,
        self::getTypeMoney,
        self::getSendType,
        self::getMinAmount,
        self::getMaxAmount,
        self::getSendStartDate,
        self::getSendEndDate,
        self::getUseStartDate,
        self::getUseEndDate,
        self::getMinGoodsAmount,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getTypeId, description: '', type: 'integer'),
        new OA\Property(property: self::getTypeName, description: '红包类型名称', type: 'string'),
        new OA\Property(property: self::getTypeMoney, description: '红包金额', type: 'string'),
        new OA\Property(property: self::getSendType, description: '发放类型', type: 'integer'),
        new OA\Property(property: self::getMinAmount, description: '最小金额', type: 'string'),
        new OA\Property(property: self::getMaxAmount, description: '最大金额', type: 'string'),
        new OA\Property(property: self::getSendStartDate, description: '发放开始时间', type: 'integer'),
        new OA\Property(property: self::getSendEndDate, description: '发放结束时间', type: 'integer'),
        new OA\Property(property: self::getUseStartDate, description: '使用开始时间', type: 'integer'),
        new OA\Property(property: self::getUseEndDate, description: '使用结束时间', type: 'integer'),
        new OA\Property(property: self::getMinGoodsAmount, description: '最小商品金额', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ActivityBonusCreateRequest extends FormRequest
{
    const string getTypeId = 'typeId';

    const string getTypeName = 'typeName';

    const string getTypeMoney = 'typeMoney';

    const string getSendType = 'sendType';

    const string getMinAmount = 'minAmount';

    const string getMaxAmount = 'maxAmount';

    const string getSendStartDate = 'sendStartDate';

    const string getSendEndDate = 'sendEndDate';

    const string getUseStartDate = 'useStartDate';

    const string getUseEndDate = 'useEndDate';

    const string getMinGoodsAmount = 'minGoodsAmount';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getTypeId => 'required',
            self::getTypeName => 'required',
            self::getTypeMoney => 'required',
            self::getSendType => 'required',
            self::getMinAmount => 'required',
            self::getMaxAmount => 'required',
            self::getSendStartDate => 'required',
            self::getSendEndDate => 'required',
            self::getUseStartDate => 'required',
            self::getUseEndDate => 'required',
            self::getMinGoodsAmount => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getTypeId.'.required' => '请设置',
            self::getTypeName.'.required' => '请设置红包类型名称',
            self::getTypeMoney.'.required' => '请设置红包金额',
            self::getSendType.'.required' => '请设置发放类型',
            self::getMinAmount.'.required' => '请设置最小金额',
            self::getMaxAmount.'.required' => '请设置最大金额',
            self::getSendStartDate.'.required' => '请设置发放开始时间',
            self::getSendEndDate.'.required' => '请设置发放结束时间',
            self::getUseStartDate.'.required' => '请设置使用开始时间',
            self::getUseEndDate.'.required' => '请设置使用结束时间',
            self::getMinGoodsAmount.'.required' => '请设置最小商品金额',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
