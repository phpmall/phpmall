<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\SubAccount;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerSubAccountStoreRequest',
    required: [
        self::getUsername,
        self::getPassword,
        self::getRealName,
        self::getPhone,
        self::getRoleIds,
    ],
    properties: [
        new OA\Property(property: self::getUsername, description: '用户名', type: 'string'),
        new OA\Property(property: self::getPassword, description: '登录密码', type: 'string'),
        new OA\Property(property: self::getRealName, description: '真实姓名', type: 'string'),
        new OA\Property(property: self::getPhone, description: '手机号', type: 'string'),
        new OA\Property(property: self::getEmail, description: '邮箱', type: 'string', nullable: true),
        new OA\Property(property: self::getRoleIds, description: '角色ID列表', type: 'array', items: new OA\Items(type: 'integer')),
    ]
)]
class SubAccountStoreRequest extends FormRequest
{
    const string getUsername = 'username';

    const string getPassword = 'password';

    const string getRealName = 'real_name';

    const string getPhone = 'phone';

    const string getEmail = 'email';

    const string getRoleIds = 'role_ids';

    public function rules(): array
    {
        return [
            self::getUsername => 'required|string|min:3|max:30',
            self::getPassword => 'required|string|min:6|max:30',
            self::getRealName => 'required|string|max:50',
            self::getPhone => 'required|string|regex:/^1[3-9]\d{9}$/',
            self::getEmail => 'nullable|string|email|max:100',
            self::getRoleIds => 'required|array',
            self::getRoleIds.'.*' => 'integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUsername.'.required' => '请填写用户名',
            self::getUsername.'.min' => '用户名至少3个字符',
            self::getUsername.'.max' => '用户名不能超过30个字符',
            self::getPassword.'.required' => '请填写登录密码',
            self::getPassword.'.min' => '密码至少6个字符',
            self::getRealName.'.required' => '请填写真实姓名',
            self::getPhone.'.required' => '请填写手机号',
            self::getPhone.'.regex' => '手机号格式不正确',
            self::getEmail.'.email' => '邮箱格式不正确',
            self::getRoleIds.'.required' => '请选择角色',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
