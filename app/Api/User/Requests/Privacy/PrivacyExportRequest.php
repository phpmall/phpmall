<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Privacy;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PrivacyExportRequest',
    required: [
        self::getType,
    ],
    properties: [
        new OA\Property(property: self::getType, description: '导出类型:all,profile,orders,transactions', type: 'string'),
        new OA\Property(property: self::getEmail, description: '接收邮箱', type: 'string', nullable: true),
    ]
)]
class PrivacyExportRequest extends FormRequest
{
    const string getType = 'type';

    const string getEmail = 'email';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getType => ['required', 'string', 'in:all,profile,orders,transactions'],
            self::getEmail => ['nullable', 'email', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getType.'.required' => '请选择导出类型',
        ];
    }
}
