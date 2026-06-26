<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Security;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SecurityUpdateEmailRequest',
    required: [
        self::getEmail,
        self::getCode,
    ],
    properties: [
        new OA\Property(property: self::getEmail, description: '新邮箱', type: 'string', format: 'email'),
        new OA\Property(property: self::getCode, description: '邮箱验证码', type: 'string'),
    ]
)]
class SecurityUpdateEmailRequest extends FormRequest
{
    const string getEmail = 'email';

    const string getCode = 'code';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getEmail => ['required', 'email', 'max:100'],
            self::getCode => ['required', 'string', 'size:6'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getEmail.'.required' => '请输入邮箱地址',
            self::getEmail.'.email' => '邮箱格式不正确',
            self::getCode.'.required' => '请输入验证码',
            self::getCode.'.size' => '验证码长度不正确',
        ];
    }
}
