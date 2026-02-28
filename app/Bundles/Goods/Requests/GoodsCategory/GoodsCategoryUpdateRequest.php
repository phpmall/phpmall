<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsCategory;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsCategoryUpdateRequest',
    required: [
        self::getCatId,
        self::getCatName,
        self::getKeywords,
        self::getCatDesc,
        self::getParentId,
        self::getSortOrder,
        self::getTemplateFile,
        self::getMeasureUnit,
        self::getShowInNav,
        self::getStyle,
        self::getIsShow,
        self::getGrade,
        self::getFilterAttr,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getCatId, description: '', type: 'integer'),
        new OA\Property(property: self::getCatName, description: '分类名称', type: 'string'),
        new OA\Property(property: self::getKeywords, description: '关键词', type: 'string'),
        new OA\Property(property: self::getCatDesc, description: '分类描述', type: 'string'),
        new OA\Property(property: self::getParentId, description: '父级ID', type: 'integer'),
        new OA\Property(property: self::getSortOrder, description: '排序', type: 'integer'),
        new OA\Property(property: self::getTemplateFile, description: '模板文件', type: 'string'),
        new OA\Property(property: self::getMeasureUnit, description: '计量单位', type: 'string'),
        new OA\Property(property: self::getShowInNav, description: '是否在导航显示', type: 'integer'),
        new OA\Property(property: self::getStyle, description: '样式', type: 'string'),
        new OA\Property(property: self::getIsShow, description: '是否显示', type: 'integer'),
        new OA\Property(property: self::getGrade, description: '等级', type: 'integer'),
        new OA\Property(property: self::getFilterAttr, description: '筛选属性', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class GoodsCategoryUpdateRequest extends FormRequest
{
    const string getCatId = 'catId';

    const string getCatName = 'catName';

    const string getKeywords = 'keywords';

    const string getCatDesc = 'catDesc';

    const string getParentId = 'parentId';

    const string getSortOrder = 'sortOrder';

    const string getTemplateFile = 'templateFile';

    const string getMeasureUnit = 'measureUnit';

    const string getShowInNav = 'showInNav';

    const string getStyle = 'style';

    const string getIsShow = 'isShow';

    const string getGrade = 'grade';

    const string getFilterAttr = 'filterAttr';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getCatId => 'required',
            self::getCatName => 'required',
            self::getKeywords => 'required',
            self::getCatDesc => 'required',
            self::getParentId => 'required',
            self::getSortOrder => 'required',
            self::getTemplateFile => 'required',
            self::getMeasureUnit => 'required',
            self::getShowInNav => 'required',
            self::getStyle => 'required',
            self::getIsShow => 'required',
            self::getGrade => 'required',
            self::getFilterAttr => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getCatId.'.required' => '请设置',
            self::getCatName.'.required' => '请设置分类名称',
            self::getKeywords.'.required' => '请设置关键词',
            self::getCatDesc.'.required' => '请设置分类描述',
            self::getParentId.'.required' => '请设置父级ID',
            self::getSortOrder.'.required' => '请设置排序',
            self::getTemplateFile.'.required' => '请设置模板文件',
            self::getMeasureUnit.'.required' => '请设置计量单位',
            self::getShowInNav.'.required' => '请设置是否在导航显示',
            self::getStyle.'.required' => '请设置样式',
            self::getIsShow.'.required' => '请设置是否显示',
            self::getGrade.'.required' => '请设置等级',
            self::getFilterAttr.'.required' => '请设置筛选属性',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
