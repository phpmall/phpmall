<?php

declare(strict_types=1);

namespace App\Bundles\Payment\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaymentQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getPayId, description: '', type: 'integer'),
        new OA\Property(property: self::getPayCode, description: '支付方式编码', type: 'string'),
    ]
)]
class PaymentQueryRequest extends FormRequest
{
    const string getPayId = 'payId';

    const string getPayCode = 'payCode';

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
