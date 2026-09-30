<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductCategory;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductCategoryUpdateRequest',
    required: [
        self::getId,
        self::getParentId,
        self::getName,
        self::getIconUrl,
        self::getSortOrder,
        self::getIsShow,
        self::getLevel,
        self::getPath,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父分类ID，0=根', type: 'integer'),
        new OA\Property(property: self::getName, description: '分类名称', type: 'string'),
        new OA\Property(property: self::getIconUrl, description: '图标', type: 'string'),
        new OA\Property(property: self::getSortOrder, description: '排序', type: 'integer'),
        new OA\Property(property: self::getIsShow, description: '是否显示：0-否，1-是', type: 'integer'),
        new OA\Property(property: self::getLevel, description: '层级：1-一级，2-二级，3-三级', type: 'integer'),
        new OA\Property(property: self::getPath, description: '层级路径', type: 'string'),
    ]
)]
class ProductCategoryUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getParentId = 'parentId';

    public const string getName = 'name';

    public const string getIconUrl = 'iconUrl';

    public const string getSortOrder = 'sortOrder';

    public const string getIsShow = 'isShow';

    public const string getLevel = 'level';

    public const string getPath = 'path';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getParentId => 'required',
            self::getName => 'required',
            self::getIconUrl => 'required',
            self::getSortOrder => 'required',
            self::getIsShow => 'required',
            self::getLevel => 'required',
            self::getPath => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getParentId.'.required' => '请设置父分类ID，0=根',
            self::getName.'.required' => '请设置分类名称',
            self::getIconUrl.'.required' => '请设置图标',
            self::getSortOrder.'.required' => '请设置排序',
            self::getIsShow.'.required' => '请设置是否显示：0-否，1-是',
            self::getLevel.'.required' => '请设置层级：1-一级，2-二级，3-三级',
            self::getPath.'.required' => '请设置层级路径',
        ];
    }
}
