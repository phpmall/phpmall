<?php

declare(strict_types=1);

namespace App\Modules\User\Requests\User;

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
        new OA\Property(property: self::getName, description: '', type: 'string'),
        new OA\Property(property: self::getEmail, description: '', type: 'string'),
        new OA\Property(property: self::getEmailVerifiedAt, description: '', type: 'string'),
        new OA\Property(property: self::getPassword, description: '', type: 'string'),
        new OA\Property(property: self::getRememberToken, description: '', type: 'string'),
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
            self::getName.'.required' => '请设置',
            self::getEmail.'.required' => '请设置',
            self::getEmailVerifiedAt.'.required' => '请设置',
            self::getPassword.'.required' => '请设置',
            self::getRememberToken.'.required' => '请设置',
        ];
    }
}
