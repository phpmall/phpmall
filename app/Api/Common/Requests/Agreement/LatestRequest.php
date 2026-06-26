<?php

declare(strict_types=1);

namespace App\Api\Common\Requests\Agreement;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CommonAgreementLatestRequest',
    properties: [
        new OA\Property(property: self::getType, description: '协议类型', type: 'string', nullable: true),
    ]
)]
class LatestRequest extends FormRequest
{
    const string getType = 'type';

    public function rules(): array
    {
        return [
            self::getType => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getType.'.max' => '协议类型不能超过50个字符',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
