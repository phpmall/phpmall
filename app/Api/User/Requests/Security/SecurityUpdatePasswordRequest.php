<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Security;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SecurityUpdatePasswordRequest',
    required: [
        self::getOldPassword,
        self::getPassword,
        self::getPasswordConfirmation,
    ],
    properties: [
        new OA\Property(property: self::getOldPassword, description: '原密码', type: 'string'),
        new OA\Property(property: self::getPassword, description: '新密码', type: 'string', format: 'password', minLength: 6),
        new OA\Property(property: self::getPasswordConfirmation, description: '确认新密码', type: 'string', format: 'password'),
    ]
)]
class SecurityUpdatePasswordRequest extends FormRequest
{
    const string getOldPassword = 'old_password';

    const string getPassword = 'password';

    const string getPasswordConfirmation = 'password_confirmation';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getOldPassword => ['required', 'string'],
            self::getPassword => ['required', 'string', 'min:6', 'max:32', 'confirmed'],
            self::getPasswordConfirmation => ['required', 'string', 'min:6', 'max:32'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getOldPassword.'.required' => '请输入原密码',
            self::getPassword.'.required' => '请输入新密码',
            self::getPassword.'.min' => '密码长度不能少于6位',
            self::getPassword.'.confirmed' => '两次输入的密码不一致',
        ];
    }
}
