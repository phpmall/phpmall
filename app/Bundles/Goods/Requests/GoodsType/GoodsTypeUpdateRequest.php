<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsType;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsTypeUpdateRequest',
    required: [
        self::getCatId,
        self::getCatName,
        self::getEnabled,
        self::getAttrGroup,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getCatId, description: '', type: 'integer'),
        new OA\Property(property: self::getCatName, description: '分类名称', type: 'string'),
        new OA\Property(property: self::getEnabled, description: '是否启用', type: 'integer'),
        new OA\Property(property: self::getAttrGroup, description: '属性分组', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class GoodsTypeUpdateRequest extends FormRequest
{
    const string getCatId = 'catId';

    const string getCatName = 'catName';

    const string getEnabled = 'enabled';

    const string getAttrGroup = 'attrGroup';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getCatId => 'required',
            self::getCatName => 'required',
            self::getEnabled => 'required',
            self::getAttrGroup => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getCatId.'.required' => '请设置',
            self::getCatName.'.required' => '请设置分类名称',
            self::getEnabled.'.required' => '请设置是否启用',
            self::getAttrGroup.'.required' => '请设置属性分组',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
