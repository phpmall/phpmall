<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Consent;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ConsentWithdrawRequest',
    required: [
        self::getType,
    ],
    properties: [
        new OA\Property(property: self::getType, description: '撤回类型:marketing,analytics,third_party', type: 'string'),
        new OA\Property(property: self::getReason, description: '撤回原因', type: 'string', nullable: true),
    ]
)]
class ConsentWithdrawRequest extends FormRequest
{
    const string getType = 'type';

    const string getReason = 'reason';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getType => ['required', 'string', 'in:marketing,analytics,third_party'],
            self::getReason => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getType.'.required' => '请选择撤回类型',
        ];
    }
}
