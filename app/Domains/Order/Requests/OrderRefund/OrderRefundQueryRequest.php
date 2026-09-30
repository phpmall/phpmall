<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderRefund;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderRefundQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getRefundNo, description: '退款单号', type: 'string'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '0=待商家处理 1=商家同意 2=商家拒绝 3=退货中 4=平台介入 5=已退款 6=已拒绝 7=用户撤销', type: 'integer'),
    ]
)]
class OrderRefundQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getRefundNo = 'refundNo';

    public const string getOrderId = 'orderId';

    public const string getUserId = 'userId';

    public const string getStatus = 'status';

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
