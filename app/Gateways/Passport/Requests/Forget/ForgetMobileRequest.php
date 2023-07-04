<?php

declare(strict_types=1);

namespace App\Gateways\Passport\Requests\Forget;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ForgetMobileRequest',
    required: ['mobile', 'captcha'],
    properties: [
        new OA\Property(property: 'mobile', description: '手机号码', type: 'string', example: '13901889999'),
        new OA\Property(property: 'captcha', description: '图片验证码', type: 'string', example: '1234'),
    ]
)]
class ForgetMobileRequest extends FormRequest
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
            'captcha' => 'required',
        ];
    }
}
