<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserCreateRequest',
    required: [
        self::getName,
        self::getEmail,
        self::getEmailVerifiedAt,
        self::getPassword,
        self::getRememberToken,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '用户名称', type: 'string'),
        new OA\Property(property: self::getEmail, description: '邮箱账号', type: 'string'),
        new OA\Property(property: self::getEmailVerifiedAt, description: '邮箱验证时间', type: 'string'),
        new OA\Property(property: self::getPassword, description: '加密密码', type: 'string'),
        new OA\Property(property: self::getRememberToken, description: '记住我Token', type: 'string'),
    ]
)]
class UserCreateRequest extends FormRequest
{
    public const string getName = 'name';

    public const string getEmail = 'email';

    public const string getEmailVerifiedAt = 'emailVerifiedAt';

    public const string getPassword = 'password';

    public const string getRememberToken = 'rememberToken';

    public function rules(): array
    {
        return [
            self::getName => 'required',
            self::getEmail => 'required',
            self::getEmailVerifiedAt => 'required',
            self::getPassword => 'required',
            self::getRememberToken => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请设置用户名称',
            self::getEmail.'.required' => '请设置邮箱账号',
            self::getEmailVerifiedAt.'.required' => '请设置邮箱验证时间',
            self::getPassword.'.required' => '请设置加密密码',
            self::getRememberToken.'.required' => '请设置记住我Token',
        ];
    }
}
