<?php

declare(strict_types=1);

namespace App\Bundles\Article\Requests\ArticleCat;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ArticleCatUpdateRequest',
    required: [
        self::getCatId,
        self::getParentId,
        self::getCatName,
        self::getCatType,
        self::getKeywords,
        self::getCatDesc,
        self::getSortOrder,
        self::getShowInNav,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getCatId, description: '', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父级ID', type: 'integer'),
        new OA\Property(property: self::getCatName, description: '分类名称', type: 'string'),
        new OA\Property(property: self::getCatType, description: '分类类型', type: 'integer'),
        new OA\Property(property: self::getKeywords, description: '关键词', type: 'string'),
        new OA\Property(property: self::getCatDesc, description: '分类描述', type: 'string'),
        new OA\Property(property: self::getSortOrder, description: '排序', type: 'integer'),
        new OA\Property(property: self::getShowInNav, description: '是否在导航显示', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ArticleCatUpdateRequest extends FormRequest
{
    const string getCatId = 'catId';

    const string getParentId = 'parentId';

    const string getCatName = 'catName';

    const string getCatType = 'catType';

    const string getKeywords = 'keywords';

    const string getCatDesc = 'catDesc';

    const string getSortOrder = 'sortOrder';

    const string getShowInNav = 'showInNav';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getCatId => 'required',
            self::getParentId => 'required',
            self::getCatName => 'required',
            self::getCatType => 'required',
            self::getKeywords => 'required',
            self::getCatDesc => 'required',
            self::getSortOrder => 'required',
            self::getShowInNav => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getCatId.'.required' => '请设置',
            self::getParentId.'.required' => '请设置父级ID',
            self::getCatName.'.required' => '请设置分类名称',
            self::getCatType.'.required' => '请设置分类类型',
            self::getKeywords.'.required' => '请设置关键词',
            self::getCatDesc.'.required' => '请设置分类描述',
            self::getSortOrder.'.required' => '请设置排序',
            self::getShowInNav.'.required' => '请设置是否在导航显示',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
