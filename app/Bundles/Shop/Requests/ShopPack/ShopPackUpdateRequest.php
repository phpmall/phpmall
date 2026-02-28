<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopPack;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopPackUpdateRequest',
    required: [
        self::getPackId,
        self::getPackName,
        self::getPackImg,
        self::getPackFee,
        self::getFreeMoney,
        self::getPackDesc,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getPackId, description: '', type: 'integer'),
        new OA\Property(property: self::getPackName, description: '包装名称', type: 'string'),
        new OA\Property(property: self::getPackImg, description: '包装图片', type: 'string'),
        new OA\Property(property: self::getPackFee, description: '包装费用', type: 'string'),
        new OA\Property(property: self::getFreeMoney, description: '免费额度', type: 'integer'),
        new OA\Property(property: self::getPackDesc, description: '包装描述', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ShopPackUpdateRequest extends FormRequest
{
    const string getPackId = 'packId';

    const string getPackName = 'packName';

    const string getPackImg = 'packImg';

    const string getPackFee = 'packFee';

    const string getFreeMoney = 'freeMoney';

    const string getPackDesc = 'packDesc';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getPackId => 'required',
            self::getPackName => 'required',
            self::getPackImg => 'required',
            self::getPackFee => 'required',
            self::getFreeMoney => 'required',
            self::getPackDesc => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getPackId.'.required' => '请设置',
            self::getPackName.'.required' => '请设置包装名称',
            self::getPackImg.'.required' => '请设置包装图片',
            self::getPackFee.'.required' => '请设置包装费用',
            self::getFreeMoney.'.required' => '请设置免费额度',
            self::getPackDesc.'.required' => '请设置包装描述',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
