<?php

declare(strict_types=1);

namespace App\Modules\Shop\Services;

use App\Modules\Shop\Repositories\ShopRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ShopService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ShopRepository $repository,
    ) {}

    public function getRepository(): ShopRepository
    {
        return $this->repository;
    }

    /**
     * 根据ID查询店铺
     */
    public function findById(int $id): ?array
    {
        $shop = $this->repository->findById($id);

        return empty($shop) ? null : $this->toArray($shop);
    }

    /**
     * 将店铺数据格式化为数组
     *
     * @param  array<string, mixed>  $shop
     */
    public function toArray(array $shop): array
    {
        return [
            'id' => (int) $shop['id'],
            'name' => $shop['name'],
            'logo' => $shop['logo_url'] ?? null,
            'description' => $shop['description'] ?? null,
            'categoryId' => null,
            'categoryName' => null,
            'rating' => null,
            'productCount' => 0,
            'soldCount' => (int) ($shop['total_order_count'] ?? 0),
            'followCount' => 0,
            'status' => (int) ($shop['status'] ?? 0),
            'createdAt' => $shop['created_at'],
        ];
    }
}
