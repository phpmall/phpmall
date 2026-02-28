<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsTypeAttribute;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsTypeAttributeUpdateRequest',
    required: [
        self::getAttrId,
        self::getCatId,
        self::getAttrName,
        self::getAttrInputType,
        self::getAttrType,
        self::getAttrValues,
        self::getAttrIndex,
        self::getSortOrder,
        self::getIsLinked,
        self::getAttrGroup,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getAttrId, description: '', type: 'integer'),
        new OA\Property(property: self::getCatId, description: '分类ID', type: 'integer'),
        new OA\Property(property: self::getAttrName, description: '属性名称', type: 'string'),
        new OA\Property(property: self::getAttrInputType, description: '属性输入类型', type: 'integer'),
        new OA\Property(property: self::getAttrType, description: '属性类型', type: 'integer'),
        new OA\Property(property: self::getAttrValues, description: '属性值', type: 'string'),
        new OA\Property(property: self::getAttrIndex, description: '属性索引', type: 'integer'),
        new OA\Property(property: self::getSortOrder, description: '排序顺序', type: 'integer'),
        new OA\Property(property: self::getIsLinked, description: '是否关联', type: 'integer'),
        new OA\Property(property: self::getAttrGroup, description: '属性分组', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class GoodsTypeAttributeUpdateRequest extends FormRequest
{
    const string getAttrId = 'attrId';

    const string getCatId = 'catId';

    const string getAttrName = 'attrName';

    const string getAttrInputType = 'attrInputType';

    const string getAttrType = 'attrType';

    const string getAttrValues = 'attrValues';

    const string getAttrIndex = 'attrIndex';

    const string getSortOrder = 'sortOrder';

    const string getIsLinked = 'isLinked';

    const string getAttrGroup = 'attrGroup';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getAttrId => 'required',
            self::getCatId => 'required',
            self::getAttrName => 'required',
            self::getAttrInputType => 'required',
            self::getAttrType => 'required',
            self::getAttrValues => 'required',
            self::getAttrIndex => 'required',
            self::getSortOrder => 'required',
            self::getIsLinked => 'required',
            self::getAttrGroup => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getAttrId.'.required' => '请设置',
            self::getCatId.'.required' => '请设置分类ID',
            self::getAttrName.'.required' => '请设置属性名称',
            self::getAttrInputType.'.required' => '请设置属性输入类型',
            self::getAttrType.'.required' => '请设置属性类型',
            self::getAttrValues.'.required' => '请设置属性值',
            self::getAttrIndex.'.required' => '请设置属性索引',
            self::getSortOrder.'.required' => '请设置排序顺序',
            self::getIsLinked.'.required' => '请设置是否关联',
            self::getAttrGroup.'.required' => '请设置属性分组',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
