<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserFeed;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserFeedUpdateRequest',
    required: [
        self::getFeedId,
        self::getUserId,
        self::getValueId,
        self::getGoodsId,
        self::getFeedType,
        self::getIsFeed,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getFeedId, description: '', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getValueId, description: '值ID', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getFeedType, description: '动态类型', type: 'integer'),
        new OA\Property(property: self::getIsFeed, description: '是否动态', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class UserFeedUpdateRequest extends FormRequest
{
    const string getFeedId = 'feedId';

    const string getUserId = 'userId';

    const string getValueId = 'valueId';

    const string getGoodsId = 'goodsId';

    const string getFeedType = 'feedType';

    const string getIsFeed = 'isFeed';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getFeedId => 'required',
            self::getUserId => 'required',
            self::getValueId => 'required',
            self::getGoodsId => 'required',
            self::getFeedType => 'required',
            self::getIsFeed => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getFeedId.'.required' => '请设置',
            self::getUserId.'.required' => '请设置用户ID',
            self::getValueId.'.required' => '请设置值ID',
            self::getGoodsId.'.required' => '请设置商品ID',
            self::getFeedType.'.required' => '请设置动态类型',
            self::getIsFeed.'.required' => '请设置是否动态',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
