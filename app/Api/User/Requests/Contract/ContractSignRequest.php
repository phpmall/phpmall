<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Contract;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContractSignRequest',
    required: [
        self::getSignature,
    ],
    properties: [
        new OA\Property(property: self::getSignature, description: '电子签名数据', type: 'string'),
        new OA\Property(property: self::getAgreed, description: '是否同意:1是', type: 'integer'),
    ]
)]
class ContractSignRequest extends FormRequest
{
    const string getSignature = 'signature';

    const string getAgreed = 'agreed';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getSignature => ['required', 'string', 'max:2000'],
            self::getAgreed => ['required', 'integer', 'in:1'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getSignature.'.required' => '请提供电子签名',
            self::getAgreed.'.required' => '请确认同意协议',
        ];
    }
}
