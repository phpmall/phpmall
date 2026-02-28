<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Requests\AdminRole;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdminRoleCreateRequest',
    required: [
        self::getRoleId,
        self::getRoleName,
        self::getActionList,
        self::getRoleDescribe,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getRoleId, description: '', type: 'integer'),
        new OA\Property(property: self::getRoleName, description: '角色名称', type: 'string'),
        new OA\Property(property: self::getActionList, description: '权限列表', type: 'string'),
        new OA\Property(property: self::getRoleDescribe, description: '角色描述', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class AdminRoleCreateRequest extends FormRequest
{
    const string getRoleId = 'roleId';

    const string getRoleName = 'roleName';

    const string getActionList = 'actionList';

    const string getRoleDescribe = 'roleDescribe';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getRoleId => 'required',
            self::getRoleName => 'required',
            self::getActionList => 'required',
            self::getRoleDescribe => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getRoleId.'.required' => '请设置',
            self::getRoleName.'.required' => '请设置角色名称',
            self::getActionList.'.required' => '请设置权限列表',
            self::getRoleDescribe.'.required' => '请设置角色描述',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
