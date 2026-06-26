<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Refund;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RefundStoreRequest',
    required: [
        self::getOrderId,
        self::getReason,
        self::getType,
    ],
    properties: [
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getReason, description: '退款原因', type: 'string'),
        new OA\Property(property: self::getType, description: '退款类型:refund,return_refund', type: 'string'),
        new OA\Property(property: self::getAmount, description: '退款金额(分)', type: 'integer', nullable: true),
        new OA\Property(
            property: self::getImages,
            description: '凭证图片',
            type: 'array',
            items: new OA\Items(type: 'string', format: 'uri'),
            nullable: true
        ),
        new OA\Property(property: self::getDescription, description: '补充说明', type: 'string', nullable: true),
    ]
)]
class RefundStoreRequest extends FormRequest
{
    const string getOrderId = 'order_id';

    const string getReason = 'reason';

    const string getType = 'type';

    const string getAmount = 'amount';

    const string getImages = 'images';

    const string getDescription = 'description';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getOrderId => ['required', 'integer', 'min:1'],
            self::getReason => ['required', 'string', 'max:500'],
            self::getType => ['required', 'string', 'in:refund,return_refund'],
            self::getAmount => ['nullable', 'integer', 'min:0'],
            self::getImages => ['nullable', 'array', 'max:9'],
            self::getImages.'.*' => ['string', 'url', 'max:500'],
            self::getDescription => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getOrderId.'.required' => '请选择订单',
            self::getReason.'.required' => '请填写退款原因',
            self::getType.'.required' => '请选择退款类型',
        ];
    }
}
