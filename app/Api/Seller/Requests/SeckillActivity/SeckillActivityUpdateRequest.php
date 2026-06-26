<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\SeckillActivity;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerSeckillActivityUpdateRequest',
    required: [
        self::getName,
        self::getProductId,
        self::getSkuId,
        self::getSeckillPrice,
        self::getStock,
        self::getStartTime,
        self::getEndTime,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '活动名称', type: 'string', maxLength: 100),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getSkuId, description: 'SKU ID', type: 'integer'),
        new OA\Property(property: self::getSeckillPrice, description: '秒杀价格(分)', type: 'integer'),
        new OA\Property(property: self::getOriginalPrice, description: '原价(分)', type: 'integer', nullable: true),
        new OA\Property(property: self::getStock, description: '秒杀库存', type: 'integer'),
        new OA\Property(property: self::getLimitPerUser, description: '每人限购数量', type: 'integer', nullable: true),
        new OA\Property(property: self::getStartTime, description: '开始时间', type: 'string', format: 'date-time'),
        new OA\Property(property: self::getEndTime, description: '结束时间', type: 'string', format: 'date-time'),
        new OA\Property(property: self::getDescription, description: '活动描述', type: 'string', nullable: true),
        new OA\Property(property: self::getStatus, description: '状态:0禁用,1启用', type: 'integer'),
    ]
)]
class SeckillActivityUpdateRequest extends FormRequest
{
    const string getName = 'name';

    const string getProductId = 'product_id';

    const string getSkuId = 'sku_id';

    const string getSeckillPrice = 'seckill_price';

    const string getOriginalPrice = 'original_price';

    const string getStock = 'stock';

    const string getLimitPerUser = 'limit_per_user';

    const string getStartTime = 'start_time';

    const string getEndTime = 'end_time';

    const string getDescription = 'description';

    const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getName => ['required', 'string', 'max:100'],
            self::getProductId => ['required', 'integer', 'min:1'],
            self::getSkuId => ['required', 'integer', 'min:1'],
            self::getSeckillPrice => ['required', 'integer', 'min:0'],
            self::getOriginalPrice => ['nullable', 'integer', 'min:0'],
            self::getStock => ['required', 'integer', 'min:1'],
            self::getLimitPerUser => ['nullable', 'integer', 'min:1'],
            self::getStartTime => ['required', 'date'],
            self::getEndTime => ['required', 'date', 'after:'.self::getStartTime],
            self::getDescription => ['nullable', 'string'],
            self::getStatus => ['required', 'integer', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请填写活动名称',
            self::getProductId.'.required' => '请选择商品',
            self::getSkuId.'.required' => '请选择SKU',
            self::getSeckillPrice.'.required' => '请填写秒杀价格',
            self::getStock.'.required' => '请填写秒杀库存',
            self::getStartTime.'.required' => '请选择开始时间',
            self::getEndTime.'.required' => '请选择结束时间',
            self::getEndTime.'.after' => '结束时间必须晚于开始时间',
            self::getStatus.'.required' => '请选择状态',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
