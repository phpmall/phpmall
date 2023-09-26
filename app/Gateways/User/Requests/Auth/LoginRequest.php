<?php

declare(strict_types=1);

namespace App\Gateways\User\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'LoginRequest',
    required: [
        'username',
        'password',
        'captcha',
        'uuid',
    ],
    properties: [
        new OA\Property(property: 'username', description: '登录用户名', type: 'string', example: '15858589988'),
        new OA\Property(property: 'password', description: '登录密码', type: 'string', example: 'md5密码'),
        new OA\Property(property: 'captcha', description: '图片验证码', type: 'string', example: '1234'),
        new OA\Property(property: 'uuid', description: '图片验证码UUID', type: 'string', example: 'abc'),
    ]
)]
class LoginRequest extends FormRequest
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
            'username' => 'required',
            'password' => 'required',
            'captcha' => 'required',
            'uuid' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => '请填写登录用户名',
            'password.required' => '请填写登录密码',
            'captcha.required' => '请填写图片验证码',
            'uuid.required' => '请填写图片验证码UUID参数',
        ];
    }
}
