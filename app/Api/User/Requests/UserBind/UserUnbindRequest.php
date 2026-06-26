<?php

declare(strict_types=1);

namespace App\Api\User\Requests\UserBind;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserUnbindRequest',
    required: [
        self::getType,
    ],
    properties: [
        new OA\Property(property: self::getType, description: '解绑类型:wechat,qq,weibo,alipay,phone,email', type: 'string'),
        new OA\Property(property: self::getPassword, description: '登录密码验证', type: 'string', nullable: true),
    ]
)]
class UserUnbindRequest extends FormRequest
{
    const string getType = 'type';

    const string getPassword = 'password';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getType => ['required', 'string', 'in:wechat,qq,weibo,alipay,phone,email'],
            self::getPassword => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getType.'.required' => '请选择解绑类型',
        ];
    }
}
