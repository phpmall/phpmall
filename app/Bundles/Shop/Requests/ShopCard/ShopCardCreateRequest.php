<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopCard;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopCardCreateRequest',
    required: [
        self::getCardId,
        self::getCardName,
        self::getCardImg,
        self::getCardFee,
        self::getFreeMoney,
        self::getCardDesc,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getCardId, description: '', type: 'integer'),
        new OA\Property(property: self::getCardName, description: '贺卡名称', type: 'string'),
        new OA\Property(property: self::getCardImg, description: '贺卡图片', type: 'string'),
        new OA\Property(property: self::getCardFee, description: '贺卡费用', type: 'string'),
        new OA\Property(property: self::getFreeMoney, description: '免费额度', type: 'string'),
        new OA\Property(property: self::getCardDesc, description: '贺卡描述', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ShopCardCreateRequest extends FormRequest
{
    const string getCardId = 'cardId';

    const string getCardName = 'cardName';

    const string getCardImg = 'cardImg';

    const string getCardFee = 'cardFee';

    const string getFreeMoney = 'freeMoney';

    const string getCardDesc = 'cardDesc';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getCardId => 'required',
            self::getCardName => 'required',
            self::getCardImg => 'required',
            self::getCardFee => 'required',
            self::getFreeMoney => 'required',
            self::getCardDesc => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getCardId.'.required' => '请设置',
            self::getCardName.'.required' => '请设置贺卡名称',
            self::getCardImg.'.required' => '请设置贺卡图片',
            self::getCardFee.'.required' => '请设置贺卡费用',
            self::getFreeMoney.'.required' => '请设置免费额度',
            self::getCardDesc.'.required' => '请设置贺卡描述',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
