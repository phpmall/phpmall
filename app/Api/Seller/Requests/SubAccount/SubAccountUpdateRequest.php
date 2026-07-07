<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\SubAccount;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerSubAccountUpdateRequest',
    properties: [
        new OA\Property(property: self::getPassword, description: '登录密码', type: 'string', nullable: true),
        new OA\Property(property: self::getRealName, description: '真实姓名', type: 'string', nullable: true),
        new OA\Property(property: self::getPhone, description: '手机号', type: 'string', nullable: true),
        new OA\Property(property: self::getEmail, description: '邮箱', type: 'string', nullable: true),
        new OA\Property(property: self::getRoleIds, description: '角色ID列表', type: 'array', nullable: true, items: new OA\Items(type: 'integer')),
    ]
)]
class SubAccountUpdateRequest extends FormRequest
{
    const string getPassword = 'password';

    const string getRealName = 'real_name';

    const string getPhone = 'phone';

    const string getEmail = 'email';

    const string getRoleIds = 'role_ids';

    public function rules(): array
    {
        return [
            self::getPassword => 'nullable|string|min:6|max:30',
            self::getRealName => 'nullable|string|max:50',
            self::getPhone => 'nullable|string|regex:/^1[3-9]\d{9}$/',
            self::getEmail => 'nullable|string|email|max:100',
            self::getRoleIds => 'nullable|array',
            self::getRoleIds.'.*' => 'integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            self::getPassword.'.min' => '密码至少6个字符',
            self::getPhone.'.regex' => '手机号格式不正确',
            self::getEmail.'.email' => '邮箱格式不正确',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
