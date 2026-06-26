<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ResetPasswordRequest',
    required: [
        self::getMobile,
        self::getCode,
        self::getPassword,
        self::getPasswordConfirmation,
    ],
    properties: [
        new OA\Property(property: self::getMobile, description: '用户手机号', type: 'string'),
        new OA\Property(property: self::getCode, description: '短信验证码', type: 'string'),
        new OA\Property(property: self::getPassword, description: '新密码', type: 'string'),
        new OA\Property(property: self::getPasswordConfirmation, description: '确认新密码', type: 'string'),
    ]
)]
class ResetPasswordRequest extends FormRequest
{
    const string getMobile = 'mobile';

    const string getCode = 'code';

    const string getPassword = 'password';

    const string getPasswordConfirmation = 'password_confirmation';

    public function rules(): array
    {
        return [
            self::getMobile => 'required|mobile',
            self::getCode => 'required|string',
            self::getPassword => 'required|min:6|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            self::getMobile.'.required' => '请填写手机号码',
            self::getCode.'.required' => '请填写短信验证码',
            self::getPassword.'.required' => '请填写新密码',
            self::getPassword.'.min' => '新密码不能少于 6 位',
            self::getPassword.'.confirmed' => '两次输入的密码不一致',
        ];
    }
}
