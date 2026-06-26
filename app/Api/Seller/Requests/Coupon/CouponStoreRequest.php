<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Coupon;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerCouponStoreRequest',
    required: [
        self::getName,
        self::getType,
        self::getAmount,
        self::getTotalQuantity,
        self::getStartTime,
        self::getEndTime,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '优惠券名称', type: 'string', maxLength: 100),
        new OA\Property(property: self::getType, description: '优惠券类型:1满减,2折扣,3无门槛', type: 'integer'),
        new OA\Property(property: self::getAmount, description: '优惠金额(分)或折扣率(如950表示95折)', type: 'integer'),
        new OA\Property(property: self::getMinOrderAmount, description: '最低订单金额(分)', type: 'integer', nullable: true),
        new OA\Property(property: self::getTotalQuantity, description: '发放总量', type: 'integer'),
        new OA\Property(property: self::getLimitPerUser, description: '每人限领数量', type: 'integer', nullable: true),
        new OA\Property(property: self::getStartTime, description: '开始时间', type: 'string', format: 'date-time'),
        new OA\Property(property: self::getEndTime, description: '结束时间', type: 'string', format: 'date-time'),
        new OA\Property(property: self::getApplicableProductIds, description: '适用商品ID列表', type: 'array', items: new OA\Items(type: 'integer'), nullable: true),
        new OA\Property(property: self::getApplicableCategoryIds, description: '适用分类ID列表', type: 'array', items: new OA\Items(type: 'integer'), nullable: true),
        new OA\Property(property: self::getDescription, description: '使用说明', type: 'string', nullable: true),
    ]
)]
class CouponStoreRequest extends FormRequest
{
    const string getName = 'name';

    const string getType = 'type';

    const string getAmount = 'amount';

    const string getMinOrderAmount = 'min_order_amount';

    const string getTotalQuantity = 'total_quantity';

    const string getLimitPerUser = 'limit_per_user';

    const string getStartTime = 'start_time';

    const string getEndTime = 'end_time';

    const string getApplicableProductIds = 'applicable_product_ids';

    const string getApplicableCategoryIds = 'applicable_category_ids';

    const string getDescription = 'description';

    public function rules(): array
    {
        return [
            self::getName => ['required', 'string', 'max:100'],
            self::getType => ['required', 'integer', 'in:1,2,3'],
            self::getAmount => ['required', 'integer', 'min:0'],
            self::getMinOrderAmount => ['nullable', 'integer', 'min:0'],
            self::getTotalQuantity => ['required', 'integer', 'min:1'],
            self::getLimitPerUser => ['nullable', 'integer', 'min:1'],
            self::getStartTime => ['required', 'date'],
            self::getEndTime => ['required', 'date', 'after:'.self::getStartTime],
            self::getApplicableProductIds => ['nullable', 'array'],
            self::getApplicableProductIds.'.*' => ['integer', 'min:1'],
            self::getApplicableCategoryIds => ['nullable', 'array'],
            self::getApplicableCategoryIds.'.*' => ['integer', 'min:1'],
            self::getDescription => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请填写优惠券名称',
            self::getType.'.required' => '请选择优惠券类型',
            self::getType.'.in' => '优惠券类型不正确',
            self::getAmount.'.required' => '请填写优惠金额',
            self::getTotalQuantity.'.required' => '请填写发放总量',
            self::getStartTime.'.required' => '请选择开始时间',
            self::getEndTime.'.required' => '请选择结束时间',
            self::getEndTime.'.after' => '结束时间必须晚于开始时间',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
