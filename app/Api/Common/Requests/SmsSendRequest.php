<?php

declare(strict_types=1);

namespace App\Api\Common\Requests;

use App\Rules\CaptchaRule;
use App\Rules\PhoneNumberRule;
use Focite\Captcha\Captcha;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SmsSendRequest',
    required: ['mobile', 'captcha', 'uuid'],
    properties: [
        new OA\Property(property: 'mobile', description: '手机号码', type: 'string', example: '13901889999'),
        new OA\Property(property: 'captcha', description: '图片验证码', type: 'string', example: '000000'),
        new OA\Property(property: 'uuid', description: '图片验证码UUID', type: 'string', example: '123456'),
    ]
)]
class SmsSendRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mobile' => ['required', new PhoneNumberRule()],
            'captcha' => ['required', new CaptchaRule()],
            'uuid' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'mobile.required' => '请输入手机号码',
            'captcha.required' => '请输入图片验证码',
            'uuid.required' => '请输入uuid参数',
        ];
    }
}
