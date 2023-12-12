<?php

declare(strict_types=1);

namespace App\Api\Auth\Requests\Reset;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ResetRequest',
    required: ['mobile', 'password', 'captcha'],
    properties: [
        new OA\Property(property: 'mobile', description: '手机号码', type: 'string', example: '13901889999'),
        new OA\Property(property: 'password', description: '登录密码', type: 'string', example: '123456aA'),
        new OA\Property(property: 'captcha', description: '图片验证码', type: 'string', example: '1234'),
    ]
)]
class ResetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'mobile' => 'required|max:255',
            'password' => 'required',
            'captcha' => 'required',
        ];
    }
}
