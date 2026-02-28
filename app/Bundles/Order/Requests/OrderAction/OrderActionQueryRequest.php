<?php

declare(strict_types=1);

namespace App\Bundles\Order\Requests\OrderAction;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderActionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getActionId, description: '', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
    ]
)]
class OrderActionQueryRequest extends FormRequest
{
    const string getActionId = 'actionId';

    const string getOrderId = 'orderId';

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
