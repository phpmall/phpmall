<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerSettingsUpdatePasswordRequest',
    required: [
        self::getOldPassword,
        self::getNewPassword,
        self::getNewPasswordConfirmation,
    ],
    properties: [
        new OA\Property(property: self::getOldPassword, description: '旧密码', type: 'string'),
        new OA\Property(property: self::getNewPassword, description: '新密码', type: 'string'),
        new OA\Property(property: self::getNewPasswordConfirmation, description: '确认新密码', type: 'string'),
    ]
)]
class SettingsUpdatePasswordRequest extends FormRequest
{
    const string getOldPassword = 'old_password';

    const string getNewPassword = 'new_password';

    const string getNewPasswordConfirmation = 'new_password_confirmation';

    public function rules(): array
    {
        return [
            self::getOldPassword => 'required|string|min:6',
            self::getNewPassword => 'required|string|min:6|confirmed',
            self::getNewPasswordConfirmation => 'required|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            self::getOldPassword.'.required' => '请填写旧密码',
            self::getOldPassword.'.min' => '旧密码至少6个字符',
            self::getNewPassword.'.required' => '请填写新密码',
            self::getNewPassword.'.min' => '新密码至少6个字符',
            self::getNewPassword.'.confirmed' => '两次输入的密码不一致',
            self::getNewPasswordConfirmation.'.required' => '请确认新密码',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
