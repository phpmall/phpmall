<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserExtendFields;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserExtendFieldsUpdateRequest',
    required: [
        self::getId,
        self::getRegFieldName,
        self::getDisOrder,
        self::getDisplay,
        self::getType,
        self::getIsNeed,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getRegFieldName, description: '注册字段名称', type: 'string'),
        new OA\Property(property: self::getDisOrder, description: '显示顺序', type: 'integer'),
        new OA\Property(property: self::getDisplay, description: '是否显示', type: 'integer'),
        new OA\Property(property: self::getType, description: '类型', type: 'integer'),
        new OA\Property(property: self::getIsNeed, description: '是否必填', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class UserExtendFieldsUpdateRequest extends FormRequest
{
    const string getId = 'id';

    const string getRegFieldName = 'regFieldName';

    const string getDisOrder = 'disOrder';

    const string getDisplay = 'display';

    const string getType = 'type';

    const string getIsNeed = 'isNeed';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getRegFieldName => 'required',
            self::getDisOrder => 'required',
            self::getDisplay => 'required',
            self::getType => 'required',
            self::getIsNeed => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getRegFieldName.'.required' => '请设置注册字段名称',
            self::getDisOrder.'.required' => '请设置显示顺序',
            self::getDisplay.'.required' => '请设置是否显示',
            self::getType.'.required' => '请设置类型',
            self::getIsNeed.'.required' => '请设置是否必填',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
