<?php

declare(strict_types=1);

namespace App\Api\Common\Requests\Sms;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: SmsCodeRequest::class,
    required: [
        self::getCaptchaId,
        self::getCaptchaCode,
    ],
    properties: [
        new OA\Property(property: self::getCaptchaId, description: '图片验证码ID', type: 'string'),
        new OA\Property(property: self::getCaptchaCode, description: '图片验证码', type: 'string'),
    ]
)]
class SmsCodeRequest extends FormRequest
{
    const string getCaptchaId = 'captchaId';

    const string getCaptchaCode = 'captchaCode';

    public function rules(): array
    {
        return [
            self::getCaptchaId => 'required',
            self::getCaptchaCode => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getCaptchaId.'.required' => '请填写图片验证码ID',
            self::getCaptchaCode.'.required' => '请填写图片验证码',
        ];
    }
}
