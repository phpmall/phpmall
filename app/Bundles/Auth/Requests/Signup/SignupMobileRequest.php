<?php

declare(strict_types=1);

namespace App\Bundles\Auth\Requests\Signup;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SignupMobileRequest',
    required: ['mobile', 'code', 'accept_term'],
    properties: [
        new OA\Property(property: 'mobile', description: '手机号码', type: 'string', example: '13901889999'),
        new OA\Property(property: 'code', description: '短信验证码', type: 'string', example: '123456'),
        new OA\Property(property: 'accept_term', description: '是否接受注册协议', type: 'bool', example: true),
    ]
)]
class SignupMobileRequest extends FormRequest
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
            'mobile' => 'required|max:11',
            'code' => 'required',
            'accept_term' => 'required',
        ];
    }
}
