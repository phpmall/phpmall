<?php

declare(strict_types=1);

namespace App\Api\Auth\Requests\Login;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'LoginMobileRequest',
    required: [
        'mobile',
        'password',
        'captcha',
        'uuid',
    ],
    properties: [
        new OA\Property(property: 'mobile', description: '登录手机号码', type: 'string', example: '15858589988'),
        new OA\Property(property: 'password', description: '登录密码', type: 'string', example: 'md5密码'),
        new OA\Property(property: 'captcha', description: '图片验证码', type: 'string', example: '1234'),
        new OA\Property(property: 'uuid', description: '图片验证码UUID', type: 'string', example: 'abc'),
    ]
)]
class LoginMobileRequest extends FormRequest
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
            'mobile' => 'required',
            'password' => 'required',
            'captcha' => 'required',
            'uuid' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'mobile.required' => '请填写手机号码',
            'password.required' => '请填写登录密码',
            'captcha.required' => '请填写图片验证码',
            'uuid.required' => '请填写图片验证码UUID参数',
        ];
    }
}
