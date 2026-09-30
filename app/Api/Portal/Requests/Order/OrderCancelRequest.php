<?php

declare(strict_types=1);

namespace App\Api\Portal\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalOrderCancelRequest',
    title: '取消订单请求参数',
    properties: [
        new OA\Property(property: 'reason', description: '取消原因', type: 'string', default: '用户自行取消', example: '信息填写有误，重新购买'),
    ]
)]
class OrderCancelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'reason' => ['nullable', 'string', 'max:255'],
        ];
    }
}
