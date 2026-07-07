<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderPreviewRequest',
    required: [
        self::getAddressId,
        self::getItems,
    ],
    properties: [
        new OA\Property(property: self::getAddressId, description: '收货地址ID', type: 'integer'),
        new OA\Property(property: self::getCouponId, description: '优惠券ID', type: 'integer', nullable: true),
        new OA\Property(
            property: self::getItems,
            description: '订单商品项',
            type: 'array',
            items: new OA\Items(type: 'object', properties: [
                new OA\Property(property: 'sku_id', description: 'SKU ID', type: 'integer'),
                new OA\Property(property: 'quantity', description: '购买数量', type: 'integer', minimum: 1),
            ])
        ),
    ]
)]
class OrderPreviewRequest extends FormRequest
{
    const string getAddressId = 'address_id';

    const string getCouponId = 'coupon_id';

    const string getItems = 'items';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getAddressId => ['required', 'integer', 'min:1'],
            self::getCouponId => ['nullable', 'integer', 'min:1'],
            self::getItems => ['required', 'array', 'min:1'],
            self::getItems.'.*.sku_id' => ['required', 'integer', 'min:1'],
            self::getItems.'.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getAddressId.'.required' => '请选择收货地址',
            self::getItems.'.required' => '请添加商品到订单',
            self::getItems.'.min' => '订单至少包含一个商品',
        ];
    }
}
