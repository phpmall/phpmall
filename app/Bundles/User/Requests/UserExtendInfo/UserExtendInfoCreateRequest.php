<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserExtendInfo;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserExtendInfoCreateRequest',
    required: [
        self::getId,
        self::getUserId,
        self::getRegFieldId,
        self::getContent,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: '', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getRegFieldId, description: '注册字段ID', type: 'integer'),
        new OA\Property(property: self::getContent, description: '内容', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class UserExtendInfoCreateRequest extends FormRequest
{
    const string getId = 'id';

    const string getUserId = 'userId';

    const string getRegFieldId = 'regFieldId';

    const string getContent = 'content';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getUserId => 'required',
            self::getRegFieldId => 'required',
            self::getContent => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置',
            self::getUserId.'.required' => '请设置用户ID',
            self::getRegFieldId.'.required' => '请设置注册字段ID',
            self::getContent.'.required' => '请设置内容',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
