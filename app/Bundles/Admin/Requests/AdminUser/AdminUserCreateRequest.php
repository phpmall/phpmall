<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Requests\AdminUser;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdminUserCreateRequest',
    required: [
        self::getUserId,
        self::getUserName,
        self::getEmail,
        self::getPassword,
        self::getEcSalt,
        self::getAddTime,
        self::getLastLogin,
        self::getLastIp,
        self::getActionList,
        self::getNavList,
        self::getLangType,
        self::getAgencyId,
        self::getSuppliersId,
        self::getTodolist,
        self::getRoleId,
        self::getRememberToken,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getUserId, description: '', type: 'integer'),
        new OA\Property(property: self::getUserName, description: '用户名', type: 'string'),
        new OA\Property(property: self::getEmail, description: '邮箱', type: 'string'),
        new OA\Property(property: self::getPassword, description: '密码', type: 'string'),
        new OA\Property(property: self::getEcSalt, description: 'EC盐值', type: 'string'),
        new OA\Property(property: self::getAddTime, description: '添加时间', type: 'integer'),
        new OA\Property(property: self::getLastLogin, description: '最后登录时间', type: 'integer'),
        new OA\Property(property: self::getLastIp, description: '最后登录IP', type: 'string'),
        new OA\Property(property: self::getActionList, description: '操作列表', type: 'string'),
        new OA\Property(property: self::getNavList, description: '导航列表', type: 'string'),
        new OA\Property(property: self::getLangType, description: '语言类型', type: 'string'),
        new OA\Property(property: self::getAgencyId, description: '办事处ID', type: 'integer'),
        new OA\Property(property: self::getSuppliersId, description: '供应商ID', type: 'integer'),
        new OA\Property(property: self::getTodolist, description: '待办事项', type: 'string'),
        new OA\Property(property: self::getRoleId, description: '角色ID', type: 'integer'),
        new OA\Property(property: self::getRememberToken, description: '', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class AdminUserCreateRequest extends FormRequest
{
    const string getUserId = 'userId';

    const string getUserName = 'userName';

    const string getEmail = 'email';

    const string getPassword = 'password';

    const string getEcSalt = 'ecSalt';

    const string getAddTime = 'addTime';

    const string getLastLogin = 'lastLogin';

    const string getLastIp = 'lastIp';

    const string getActionList = 'actionList';

    const string getNavList = 'navList';

    const string getLangType = 'langType';

    const string getAgencyId = 'agencyId';

    const string getSuppliersId = 'suppliersId';

    const string getTodolist = 'todolist';

    const string getRoleId = 'roleId';

    const string getRememberToken = 'rememberToken';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getUserId => 'required',
            self::getUserName => 'required',
            self::getEmail => 'required',
            self::getPassword => 'required',
            self::getEcSalt => 'required',
            self::getAddTime => 'required',
            self::getLastLogin => 'required',
            self::getLastIp => 'required',
            self::getActionList => 'required',
            self::getNavList => 'required',
            self::getLangType => 'required',
            self::getAgencyId => 'required',
            self::getSuppliersId => 'required',
            self::getTodolist => 'required',
            self::getRoleId => 'required',
            self::getRememberToken => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUserId.'.required' => '请设置',
            self::getUserName.'.required' => '请设置用户名',
            self::getEmail.'.required' => '请设置邮箱',
            self::getPassword.'.required' => '请设置密码',
            self::getEcSalt.'.required' => '请设置EC盐值',
            self::getAddTime.'.required' => '请设置添加时间',
            self::getLastLogin.'.required' => '请设置最后登录时间',
            self::getLastIp.'.required' => '请设置最后登录IP',
            self::getActionList.'.required' => '请设置操作列表',
            self::getNavList.'.required' => '请设置导航列表',
            self::getLangType.'.required' => '请设置语言类型',
            self::getAgencyId.'.required' => '请设置办事处ID',
            self::getSuppliersId.'.required' => '请设置供应商ID',
            self::getTodolist.'.required' => '请设置待办事项',
            self::getRoleId.'.required' => '请设置角色ID',
            self::getRememberToken.'.required' => '请设置',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
