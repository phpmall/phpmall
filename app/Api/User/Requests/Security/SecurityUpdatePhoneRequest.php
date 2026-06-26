<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Security;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SecurityUpdatePhoneRequest',
    required: [
        self::getPhone,
        self::getCode,
    ],
    properties: [
        new OA\Property(property: self::getPhone, description: '新手机号', type: 'string'),
        new OA\Property(property: self::getCode, description: '短信验证码', type: 'string'),
    ]
)]
class SecurityUpdatePhoneRequest extends FormRequest
{
    const string getPhone = 'phone';

    const string getCode = 'code';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getPhone => ['required', 'string', 'regex:/^1[3-9]\d{9}$/'],
            self::getCode => ['required', 'string', 'size:6'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getPhone.'.required' => '请输入手机号',
            self::getPhone.'.regex' => '手机号格式不正确',
            self::getCode.'.required' => '请输入验证码',
            self::getCode.'.size' => '验证码长度不正确',
        ];
    }
}
