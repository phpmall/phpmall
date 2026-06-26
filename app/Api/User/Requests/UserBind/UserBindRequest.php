<?php

declare(strict_types=1);

namespace App\Api\User\Requests\UserBind;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserBindRequest',
    required: [
        self::getType,
        self::getAccount,
    ],
    properties: [
        new OA\Property(property: self::getType, description: '绑定类型:wechat,qq,weibo,alipay,phone,email', type: 'string'),
        new OA\Property(property: self::getAccount, description: '第三方账号标识', type: 'string'),
        new OA\Property(property: self::getNickname, description: '第三方昵称', type: 'string', nullable: true),
        new OA\Property(property: self::getAvatar, description: '第三方头像', type: 'string', format: 'uri', nullable: true),
        new OA\Property(property: self::getCode, description: '验证码', type: 'string', nullable: true),
    ]
)]
class UserBindRequest extends FormRequest
{
    const string getType = 'type';

    const string getAccount = 'account';

    const string getNickname = 'nickname';

    const string getAvatar = 'avatar';

    const string getCode = 'code';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getType => ['required', 'string', 'in:wechat,qq,weibo,alipay,phone,email'],
            self::getAccount => ['required', 'string', 'max:255'],
            self::getNickname => ['nullable', 'string', 'max:100'],
            self::getAvatar => ['nullable', 'string', 'url', 'max:500'],
            self::getCode => ['nullable', 'string', 'max:10'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getType.'.required' => '请选择绑定类型',
            self::getAccount.'.required' => '请输入账号信息',
        ];
    }
}
