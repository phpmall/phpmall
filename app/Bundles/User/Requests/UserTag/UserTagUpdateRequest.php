<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserTag;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserTagUpdateRequest',
    required: [
        self::getTagId,
        self::getUserId,
        self::getGoodsId,
        self::getTagWords,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getTagId, description: '', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getTagWords, description: '标签词', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class UserTagUpdateRequest extends FormRequest
{
    const string getTagId = 'tagId';

    const string getUserId = 'userId';

    const string getGoodsId = 'goodsId';

    const string getTagWords = 'tagWords';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getTagId => 'required',
            self::getUserId => 'required',
            self::getGoodsId => 'required',
            self::getTagWords => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getTagId.'.required' => '请设置',
            self::getUserId.'.required' => '请设置用户ID',
            self::getGoodsId.'.required' => '请设置商品ID',
            self::getTagWords.'.required' => '请设置标签词',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
