<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\Goods;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getGoodsId, description: '', type: 'integer'),
        new OA\Property(property: self::getCatId, description: '商品分类ID', type: 'integer'),
        new OA\Property(property: self::getGoodsSn, description: '商品编码', type: 'string'),
        new OA\Property(property: self::getBrandId, description: '商品品牌ID', type: 'integer'),
        new OA\Property(property: self::getGoodsNumber, description: '商品库存', type: 'integer'),
        new OA\Property(property: self::getGoodsWeight, description: '商品重量', type: 'string'),
        new OA\Property(property: self::getPromoteStartDate, description: '促销开始时间', type: 'integer'),
        new OA\Property(property: self::getPromoteEndDate, description: '促销结束时间', type: 'integer'),
        new OA\Property(property: self::getIsOnSale, description: '是否上架', type: 'integer'),
        new OA\Property(property: self::getSortOrder, description: '排序', type: 'integer'),
        new OA\Property(property: self::getLastUpdate, description: '最后更新时间', type: 'integer'),
    ]
)]
class GoodsQueryRequest extends FormRequest
{
    const string getGoodsId = 'goodsId';

    const string getCatId = 'catId';

    const string getGoodsSn = 'goodsSn';

    const string getBrandId = 'brandId';

    const string getGoodsNumber = 'goodsNumber';

    const string getGoodsWeight = 'goodsWeight';

    const string getPromoteStartDate = 'promoteStartDate';

    const string getPromoteEndDate = 'promoteEndDate';

    const string getIsOnSale = 'isOnSale';

    const string getSortOrder = 'sortOrder';

    const string getLastUpdate = 'lastUpdate';

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
