<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserCollect;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserCollectCreateRequest',
    required: [
        self::getRecId,
        self::getUserId,
        self::getGoodsId,
        self::getAddTime,
        self::getIsAttention,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getRecId, description: '', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getAddTime, description: '添加时间', type: 'integer'),
        new OA\Property(property: self::getIsAttention, description: '是否关注', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class UserCollectCreateRequest extends FormRequest
{
    const string getRecId = 'recId';

    const string getUserId = 'userId';

    const string getGoodsId = 'goodsId';

    const string getAddTime = 'addTime';

    const string getIsAttention = 'isAttention';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getRecId => 'required',
            self::getUserId => 'required',
            self::getGoodsId => 'required',
            self::getAddTime => 'required',
            self::getIsAttention => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getRecId.'.required' => '请设置',
            self::getUserId.'.required' => '请设置用户ID',
            self::getGoodsId.'.required' => '请设置商品ID',
            self::getAddTime.'.required' => '请设置添加时间',
            self::getIsAttention.'.required' => '请设置是否关注',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
