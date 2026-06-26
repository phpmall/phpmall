<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SignupRequest',
    required: [
        self::getMobile,
        self::getPassword,
        self::getPasswordConfirmation,
        self::getDeviceName,
    ],
    properties: [
        new OA\Property(property: self::getMobile, description: '用户手机号', type: 'string'),
        new OA\Property(property: self::getPassword, description: '登录密码', type: 'string'),
        new OA\Property(property: self::getPasswordConfirmation, description: '确认密码', type: 'string'),
        new OA\Property(property: self::getDeviceName, description: '设备名称', type: 'string'),
    ]
)]
class SignupRequest extends FormRequest
{
    const string getMobile = 'mobile';

    const string getPassword = 'password';

    const string getPasswordConfirmation = 'password_confirmation';

    const string getDeviceName = 'device_name';

    public function rules(): array
    {
        return [
            self::getMobile => 'required|mobile',
            self::getPassword => 'required|min:6|confirmed',
            self::getDeviceName => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getMobile.'.required' => '请填写手机号码',
            self::getPassword.'.required' => '请填写登录密码',
            self::getPassword.'.min' => '登录密码不能少于 6 位',
            self::getPassword.'.confirmed' => '两次输入的密码不一致',
            self::getDeviceName.'.required' => '请填写设备信息',
        ];
    }
}
