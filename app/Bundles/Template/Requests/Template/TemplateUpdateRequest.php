<?php

declare(strict_types=1);

namespace App\Bundles\Template\Requests\Template;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'TemplateUpdateRequest',
    required: [
        self::getId,
        self::getFilename,
        self::getRegion,
        self::getLibrary,
        self::getSortOrder,
        self::getIdValue,
        self::getNumber,
        self::getType,
        self::getTheme,
        self::getRemarks,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getFilename, description: '文件名', type: 'string'),
        new OA\Property(property: self::getRegion, description: '区域', type: 'string'),
        new OA\Property(property: self::getLibrary, description: '库', type: 'string'),
        new OA\Property(property: self::getSortOrder, description: '排序顺序', type: 'integer'),
        new OA\Property(property: self::getIdValue, description: '关联ID', type: 'integer'),
        new OA\Property(property: self::getNumber, description: '数量', type: 'integer'),
        new OA\Property(property: self::getType, description: '类型', type: 'integer'),
        new OA\Property(property: self::getTheme, description: '主题', type: 'string'),
        new OA\Property(property: self::getRemarks, description: '备注', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class TemplateUpdateRequest extends FormRequest
{
    const string getId = 'id';

    const string getFilename = 'filename';

    const string getRegion = 'region';

    const string getLibrary = 'library';

    const string getSortOrder = 'sortOrder';

    const string getIdValue = 'idValue';

    const string getNumber = 'number';

    const string getType = 'type';

    const string getTheme = 'theme';

    const string getRemarks = 'remarks';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getFilename => 'required',
            self::getRegion => 'required',
            self::getLibrary => 'required',
            self::getSortOrder => 'required',
            self::getIdValue => 'required',
            self::getNumber => 'required',
            self::getType => 'required',
            self::getTheme => 'required',
            self::getRemarks => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getFilename.'.required' => '请设置文件名',
            self::getRegion.'.required' => '请设置区域',
            self::getLibrary.'.required' => '请设置库',
            self::getSortOrder.'.required' => '请设置排序顺序',
            self::getIdValue.'.required' => '请设置关联ID',
            self::getNumber.'.required' => '请设置数量',
            self::getType.'.required' => '请设置类型',
            self::getTheme.'.required' => '请设置主题',
            self::getRemarks.'.required' => '请设置备注',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
