<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Privacy;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PrivacyCorrectRequest',
    required: [
        self::getField,
        self::getValue,
    ],
    properties: [
        new OA\Property(property: self::getField, description: '需要修正的字段', type: 'string'),
        new OA\Property(property: self::getValue, description: '修正后的值', type: 'string'),
        new OA\Property(property: self::getReason, description: '修正原因', type: 'string', nullable: true),
    ]
)]
class PrivacyCorrectRequest extends FormRequest
{
    const string getField = 'field';

    const string getValue = 'value';

    const string getReason = 'reason';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getField => ['required', 'string', 'max:100'],
            self::getValue => ['required', 'string', 'max:500'],
            self::getReason => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getField.'.required' => '请选择需要修正的字段',
            self::getValue.'.required' => '请填写修正后的值',
        ];
    }
}
