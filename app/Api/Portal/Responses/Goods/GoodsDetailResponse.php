<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\Goods;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalGoodsDetailResponse',
    title: '商品详情完整出参',
    description: '聚合 SPU、全部可用 SKU 规格价格矩阵、分类信息及评价概要'
)]
class GoodsDetailResponse
{
    #[OA\Property(
        property: 'product',
        description: 'SPU 基础信息',
        type: 'object',
        properties: [
            new OA\Property(property: 'id', type: 'integer', example: 1001),
            new OA\Property(property: 'title', type: 'string', example: '旗舰智能手机'),
            new OA\Property(property: 'subtitle', type: 'string', nullable: true, example: '徕卡影像 潜望长焦'),
            new OA\Property(property: 'description', type: 'string', nullable: true, example: '<p>图文详情...</p>'),
            new OA\Property(property: 'main_image', type: 'string', example: '/images/phone.jpg'),
            new OA\Property(property: 'images', type: 'array', items: new OA\Items(type: 'string')),
            new OA\Property(property: 'min_price', type: 'integer', example: 499900),
            new OA\Property(property: 'max_price', type: 'integer', example: 699900),
            new OA\Property(property: 'sales_count', type: 'integer', example: 3820),
            new OA\Property(property: 'total_stock', type: 'integer', example: 500),
        ]
    )]
    public array $product;

    #[OA\Property(
        property: 'skus',
        description: '可用 SKU 规格列表',
        type: 'array',
        items: new OA\Items(
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 2001),
                new OA\Property(property: 'sku_code', type: 'string', example: 'SKU-PHONE-BLK-512'),
                new OA\Property(property: 'sku_specs', type: 'object', example: ['颜色' => '曜石黑', '内存' => '16G+512G']),
                new OA\Property(property: 'price', type: 'integer', example: 549900),
                new OA\Property(property: 'market_price', type: 'integer', example: 599900),
                new OA\Property(property: 'stock', type: 'integer', example: 88),
                new OA\Property(property: 'image', type: 'string', nullable: true, example: '/images/phone-black.jpg'),
            ]
        )
    )]
    public array $skus;

    #[OA\Property(
        property: 'category',
        description: '所属分类',
        type: 'object',
        nullable: true,
        properties: [
            new OA\Property(property: 'id', type: 'integer', example: 10),
            new OA\Property(property: 'name', type: 'string', example: '智能手机'),
        ]
    )]
    public ?array $category = null;

    #[OA\Property(property: 'reviews_count', description: '评价总数', type: 'integer', example: 128)]
    public int $reviews_count;

    #[OA\Property(property: 'avg_rating', description: '平均评分（满分5分）', type: 'number', format: 'float', example: 4.9)]
    public float $avg_rating;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function fromDetail(array $data): array
    {
        $product = $data['product'] ?? [];
        if (isset($product['images']) && is_string($product['images'])) {
            $product['images'] = json_decode($product['images'], true) ?? [];
        }

        $skus = array_map(function ($sku) {
            if (isset($sku['sku_specs']) && is_string($sku['sku_specs'])) {
                $sku['sku_specs'] = json_decode($sku['sku_specs'], true) ?? [];
            }

            return $sku;
        }, $data['skus'] ?? []);

        return [
            'product' => $product,
            'skus' => $skus,
            'category' => $data['category'] ?? null,
            'reviews_count' => (int) ($data['reviews_count'] ?? 0),
            'avg_rating' => (float) ($data['avg_rating'] ?? 5.0),
        ];
    }
}
