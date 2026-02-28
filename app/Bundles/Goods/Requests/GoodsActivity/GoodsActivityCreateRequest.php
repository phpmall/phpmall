<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsActivity;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsActivityCreateRequest',
    required: [
        self::getActId,
        self::getActName,
        self::getActDesc,
        self::getActType,
        self::getGoodsId,
        self::getProductId,
        self::getGoodsName,
        self::getStartTime,
        self::getEndTime,
        self::getIsFinished,
        self::getExtInfo,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getActId, description: '', type: 'integer'),
        new OA\Property(property: self::getActName, description: '活动名称', type: 'string'),
        new OA\Property(property: self::getActDesc, description: '活动描述', type: 'string'),
        new OA\Property(property: self::getActType, description: '活动类型', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '货品ID', type: 'integer'),
        new OA\Property(property: self::getGoodsName, description: '商品名称', type: 'string'),
        new OA\Property(property: self::getStartTime, description: '开始时间', type: 'integer'),
        new OA\Property(property: self::getEndTime, description: '结束时间', type: 'integer'),
        new OA\Property(property: self::getIsFinished, description: '是否结束', type: 'integer'),
        new OA\Property(property: self::getExtInfo, description: '扩展信息', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class GoodsActivityCreateRequest extends FormRequest
{
    const string getActId = 'actId';

    const string getActName = 'actName';

    const string getActDesc = 'actDesc';

    const string getActType = 'actType';

    const string getGoodsId = 'goodsId';

    const string getProductId = 'productId';

    const string getGoodsName = 'goodsName';

    const string getStartTime = 'startTime';

    const string getEndTime = 'endTime';

    const string getIsFinished = 'isFinished';

    const string getExtInfo = 'extInfo';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getActId => 'required',
            self::getActName => 'required',
            self::getActDesc => 'required',
            self::getActType => 'required',
            self::getGoodsId => 'required',
            self::getProductId => 'required',
            self::getGoodsName => 'required',
            self::getStartTime => 'required',
            self::getEndTime => 'required',
            self::getIsFinished => 'required',
            self::getExtInfo => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getActId.'.required' => '请设置',
            self::getActName.'.required' => '请设置活动名称',
            self::getActDesc.'.required' => '请设置活动描述',
            self::getActType.'.required' => '请设置活动类型',
            self::getGoodsId.'.required' => '请设置商品ID',
            self::getProductId.'.required' => '请设置货品ID',
            self::getGoodsName.'.required' => '请设置商品名称',
            self::getStartTime.'.required' => '请设置开始时间',
            self::getEndTime.'.required' => '请设置结束时间',
            self::getIsFinished.'.required' => '请设置是否结束',
            self::getExtInfo.'.required' => '请设置扩展信息',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
