<?php

declare(strict_types=1);

namespace App\Modules\Product\Services;

use App\Modules\Product\Repositories\ProductCategoryRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ProductCategoryService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ProductCategoryRepository $repository,
    ) {}

    public function getRepository(): ProductCategoryRepository
    {
        return $this->repository;
    }

    /**
     * 获取前台展示的分类树
     */
    public function getTree(): array
    {
        $categories = $this->repository->findAll(['is_show' => 1], 'sort_order', 'asc');

        return $this->buildTree($categories);
    }

    private function buildTree(array $categories, int $parentId = 0): array
    {
        $tree = [];

        foreach ($categories as $category) {
            if ((int) $category['parent_id'] !== $parentId) {
                continue;
            }

            $tree[] = [
                'id' => (int) $category['id'],
                'name' => $category['name'],
                'icon' => $category['icon_url'] ?? null,
                'image' => null,
                'sort' => (int) $category['sort_order'],
                'children' => $this->buildTree($categories, (int) $category['id']),
            ];
        }

        return $tree;
    }
}
