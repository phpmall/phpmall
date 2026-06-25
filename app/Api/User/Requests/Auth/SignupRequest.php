<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: SignupRequest::class,
    required: [

    ],
    properties: [

    ]
)]
class SignupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mobile' => 'required|mobile',
            'password' => 'required',
            'device_name' => 'required',
        ];
    }

    public function messages(): array
    {
        return [

        ];
    }
}
