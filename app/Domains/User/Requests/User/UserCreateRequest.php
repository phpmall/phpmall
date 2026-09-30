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
        self::getPhone,
        self::getPhoneVerifiedAt,
        self::getEmailVerifiedAt,
        self::getPassword,
        self::getStatus,
        self::getAvatar,
        self::getNickname,
        self::getRememberToken,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '', type: 'string'),
        new OA\Property(property: self::getEmail, description: '', type: 'string'),
        new OA\Property(property: self::getPhone, description: '手机号', type: 'string'),
        new OA\Property(property: self::getPhoneVerifiedAt, description: '手机号验证时间', type: 'string'),
        new OA\Property(property: self::getEmailVerifiedAt, description: '', type: 'string'),
        new OA\Property(property: self::getPassword, description: '', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态：1-正常 2-禁用', type: 'integer'),
        new OA\Property(property: self::getAvatar, description: '头像', type: 'string'),
        new OA\Property(property: self::getNickname, description: '昵称', type: 'string'),
        new OA\Property(property: self::getRememberToken, description: '', type: 'string'),
    ]
)]
class UserCreateRequest extends FormRequest
{
    public const string getName = 'name';

    public const string getEmail = 'email';

    public const string getPhone = 'phone';

    public const string getPhoneVerifiedAt = 'phoneVerifiedAt';

    public const string getEmailVerifiedAt = 'emailVerifiedAt';

    public const string getPassword = 'password';

    public const string getStatus = 'status';

    public const string getAvatar = 'avatar';

    public const string getNickname = 'nickname';

    public const string getRememberToken = 'rememberToken';

    public function rules(): array
    {
        return [
            self::getName => 'required',
            self::getEmail => 'required',
            self::getPhone => 'required',
            self::getPhoneVerifiedAt => 'required',
            self::getEmailVerifiedAt => 'required',
            self::getPassword => 'required',
            self::getStatus => 'required',
            self::getAvatar => 'required',
            self::getNickname => 'required',
            self::getRememberToken => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请设置',
            self::getEmail.'.required' => '请设置',
            self::getPhone.'.required' => '请设置手机号',
            self::getPhoneVerifiedAt.'.required' => '请设置手机号验证时间',
            self::getEmailVerifiedAt.'.required' => '请设置',
            self::getPassword.'.required' => '请设置',
            self::getStatus.'.required' => '请设置状态：1-正常 2-禁用',
            self::getAvatar.'.required' => '请设置头像',
            self::getNickname.'.required' => '请设置昵称',
            self::getRememberToken.'.required' => '请设置',
        ];
    }
}
