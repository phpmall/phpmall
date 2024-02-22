<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Requests\Login;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'LoginSmsRequest',
    required: ['mobile', 'code'],
    properties: [
        new OA\Property(property: 'mobile', description: '手机号码', type: 'string', example: '13901889999'),
        new OA\Property(property: 'code', description: '短信验证码', type: 'string', example: '123456'),
    ]
)]
class LoginSmsRequest extends FormRequest
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
        ];
    }
}
