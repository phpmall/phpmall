<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getOrderNo, description: '订单号', type: 'string'),
        new OA\Property(property: self::getStatus, description: '订单状态：10-待付款，20-已支付，30-待发货，40-已发货，50-待收货，60-已收货，70-已完成，80-已取消，90-退款中，100-已退款', type: 'integer'),
        new OA\Property(property: self::getPayStatus, description: '支付状态：0-未支付，20-已支付，30-部分退款，100-全额退款', type: 'integer'),
        new OA\Property(property: self::getPayTime, description: '支付时间', type: 'string'),
        new OA\Property(property: self::getCreatedAt, description: '创建时间', type: 'string'),
    ]
)]
class OrderQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getOrderNo = 'orderNo';

    public const string getStatus = 'status';

    public const string getPayStatus = 'payStatus';

    public const string getPayTime = 'payTime';

    public const string getCreatedAt = 'createdAt';

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
