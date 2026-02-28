<?php

declare(strict_types=1);

namespace App\Bundles\Order\Requests\OrderBackOrder;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderBackOrderQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getBackId, description: '', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
    ]
)]
class OrderBackOrderQueryRequest extends FormRequest
{
    const string getBackId = 'backId';

    const string getOrderId = 'orderId';

    const string getUserId = 'userId';

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
