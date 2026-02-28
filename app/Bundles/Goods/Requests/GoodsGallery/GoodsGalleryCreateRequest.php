<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsGallery;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsGalleryCreateRequest',
    required: [
        self::getImgId,
        self::getGoodsId,
        self::getImgUrl,
        self::getImgDesc,
        self::getThumbUrl,
        self::getImgOriginal,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getImgId, description: '', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getImgUrl, description: '图片URL', type: 'string'),
        new OA\Property(property: self::getImgDesc, description: '图片描述', type: 'string'),
        new OA\Property(property: self::getThumbUrl, description: '缩略图URL', type: 'string'),
        new OA\Property(property: self::getImgOriginal, description: '原始图片', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class GoodsGalleryCreateRequest extends FormRequest
{
    const string getImgId = 'imgId';

    const string getGoodsId = 'goodsId';

    const string getImgUrl = 'imgUrl';

    const string getImgDesc = 'imgDesc';

    const string getThumbUrl = 'thumbUrl';

    const string getImgOriginal = 'imgOriginal';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getImgId => 'required',
            self::getGoodsId => 'required',
            self::getImgUrl => 'required',
            self::getImgDesc => 'required',
            self::getThumbUrl => 'required',
            self::getImgOriginal => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getImgId.'.required' => '请设置',
            self::getGoodsId.'.required' => '请设置商品ID',
            self::getImgUrl.'.required' => '请设置图片URL',
            self::getImgDesc.'.required' => '请设置图片描述',
            self::getThumbUrl.'.required' => '请设置缩略图URL',
            self::getImgOriginal.'.required' => '请设置原始图片',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
