<?php

declare(strict_types=1);

namespace App\Bundles\Order\Requests\OrderInfo;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderInfoQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getOrderId, description: '', type: 'integer'),
        new OA\Property(property: self::getOrderSn, description: '订单编号', type: 'string'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getOrderStatus, description: '订单状态', type: 'integer'),
        new OA\Property(property: self::getShippingStatus, description: '配送状态', type: 'integer'),
        new OA\Property(property: self::getPayStatus, description: '支付状态', type: 'integer'),
        new OA\Property(property: self::getShippingId, description: '配送方式ID', type: 'integer'),
        new OA\Property(property: self::getPayId, description: '支付方式ID', type: 'integer'),
        new OA\Property(property: self::getExtensionId, description: '扩展ID', type: 'integer'),
        new OA\Property(property: self::getAgencyId, description: '办事处ID', type: 'integer'),
    ]
)]
class OrderInfoQueryRequest extends FormRequest
{
    const string getOrderId = 'orderId';

    const string getOrderSn = 'orderSn';

    const string getUserId = 'userId';

    const string getOrderStatus = 'orderStatus';

    const string getShippingStatus = 'shippingStatus';

    const string getPayStatus = 'payStatus';

    const string getShippingId = 'shippingId';

    const string getPayId = 'payId';

    const string getExtensionId = 'extensionId';

    const string getAgencyId = 'agencyId';

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
