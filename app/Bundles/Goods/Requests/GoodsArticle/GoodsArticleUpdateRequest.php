<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsArticle;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsArticleUpdateRequest',
    required: [
        self::getId,
        self::getGoodsId,
        self::getArticleId,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getArticleId, description: '文章ID', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class GoodsArticleUpdateRequest extends FormRequest
{
    const string getId = 'id';

    const string getGoodsId = 'goodsId';

    const string getArticleId = 'articleId';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getGoodsId => 'required',
            self::getArticleId => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getGoodsId.'.required' => '请设置商品ID',
            self::getArticleId.'.required' => '请设置文章ID',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
