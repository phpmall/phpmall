<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsVirtualCard;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsVirtualCardCreateRequest',
    required: [
        self::getCardId,
        self::getGoodsId,
        self::getCardSn,
        self::getCardPassword,
        self::getAddDate,
        self::getEndDate,
        self::getIsSaled,
        self::getOrderSn,
        self::getCrc32,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getCardId, description: '', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getCardSn, description: '卡号', type: 'string'),
        new OA\Property(property: self::getCardPassword, description: '卡密', type: 'string'),
        new OA\Property(property: self::getAddDate, description: '添加日期', type: 'integer'),
        new OA\Property(property: self::getEndDate, description: '结束日期', type: 'integer'),
        new OA\Property(property: self::getIsSaled, description: '是否已售', type: 'integer'),
        new OA\Property(property: self::getOrderSn, description: '订单号', type: 'string'),
        new OA\Property(property: self::getCrc32, description: 'CRC32校验', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class GoodsVirtualCardCreateRequest extends FormRequest
{
    const string getCardId = 'cardId';

    const string getGoodsId = 'goodsId';

    const string getCardSn = 'cardSn';

    const string getCardPassword = 'cardPassword';

    const string getAddDate = 'addDate';

    const string getEndDate = 'endDate';

    const string getIsSaled = 'isSaled';

    const string getOrderSn = 'orderSn';

    const string getCrc32 = 'crc32';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getCardId => 'required',
            self::getGoodsId => 'required',
            self::getCardSn => 'required',
            self::getCardPassword => 'required',
            self::getAddDate => 'required',
            self::getEndDate => 'required',
            self::getIsSaled => 'required',
            self::getOrderSn => 'required',
            self::getCrc32 => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getCardId.'.required' => '请设置',
            self::getGoodsId.'.required' => '请设置商品ID',
            self::getCardSn.'.required' => '请设置卡号',
            self::getCardPassword.'.required' => '请设置卡密',
            self::getAddDate.'.required' => '请设置添加日期',
            self::getEndDate.'.required' => '请设置结束日期',
            self::getIsSaled.'.required' => '请设置是否已售',
            self::getOrderSn.'.required' => '请设置订单号',
            self::getCrc32.'.required' => '请设置CRC32校验',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
