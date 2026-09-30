<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\Goods;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalGoodsItemResponse',
    title: '商品条目出参',
    description: '前台商品列表卡片数据'
)]
class GoodsItemResponse
{
    #[OA\Property(property: 'id', description: '商品ID', type: 'integer', example: 1001)]
    public int $id;

    #[OA\Property(property: 'title', description: '商品标题', type: 'string', example: '旗舰智能手机')]
    public string $title;

    #[OA\Property(property: 'subtitle', description: '副标题', type: 'string', nullable: true, example: '骁龙8 Gen3 徕卡影像')]
    public ?string $subtitle = null;

    #[OA\Property(property: 'main_image', description: '主图URL', type: 'string', example: '/images/phone.jpg')]
    public string $main_image;

    #[OA\Property(property: 'min_price', description: '最低售价（分）', type: 'integer', example: 499900)]
    public int $min_price;

    #[OA\Property(property: 'max_price', description: '最高售价（分）', type: 'integer', example: 699900)]
    public int $max_price;

    #[OA\Property(property: 'sales_count', description: '销量', type: 'integer', example: 3820)]
    public int $sales_count;

    #[OA\Property(property: 'is_hot', description: '是否热销：0-否，1-是', type: 'integer', example: 1)]
    public int $is_hot;

    #[OA\Property(property: 'is_new', description: '是否新品：0-否，1-是', type: 'integer', example: 1)]
    public int $is_new;

    #[OA\Property(property: 'is_recommend', description: '是否推荐：0-否，1-是', type: 'integer', example: 1)]
    public int $is_recommend;

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): array
    {
        return [
            'id' => (int) $data['id'],
            'title' => (string) $data['title'],
            'subtitle' => isset($data['subtitle']) ? (string) $data['subtitle'] : null,
            'main_image' => (string) ($data['main_image'] ?? ''),
            'min_price' => (int) ($data['min_price'] ?? 0),
            'max_price' => (int) ($data['max_price'] ?? 0),
            'sales_count' => (int) ($data['sales_count'] ?? 0),
            'is_hot' => (int) ($data['is_hot'] ?? 0),
            'is_new' => (int) ($data['is_new'] ?? 0),
            'is_recommend' => (int) ($data['is_recommend'] ?? 0),
        ];
    }
}
