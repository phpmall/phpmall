<?php

declare(strict_types=1);

namespace App\Bundles\Order\Requests\OrderDeliveryOrder;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderDeliveryOrderQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getDeliveryId, description: '', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
    ]
)]
class OrderDeliveryOrderQueryRequest extends FormRequest
{
    const string getDeliveryId = 'deliveryId';

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
