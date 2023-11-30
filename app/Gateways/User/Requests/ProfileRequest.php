<?php

declare(strict_types=1);

namespace App\Gateways\User\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProfileRequest',
    required: [
        'name',
    ],
    properties: [
        new OA\Property(property: 'name', description: '名称', type: 'string', example: '名称'),
    ]
)]
class ProfileRequest extends FormRequest
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
            'name' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '请填写名称',
        ];
    }
}
