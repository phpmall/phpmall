<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ForgotPasswordRequest',
    required: [
        self::getMobile,

    ],
    properties: [
        new OA\Property(property: self::getMobile, description: '用户手机号', type: 'string'),

    ]
)]
class ForgotPasswordRequest extends FormRequest
{
    const string getMobile = 'mobile';

    public function rules(): array
    {
        return [

        ];
    }

    public function messages(): array
    {
        return [

        ];
    }
}
