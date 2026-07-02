<?php

declare(strict_types=1);

namespace App\Modules\Product\Repositories;

use App\Modules\Product\Entities\ProductCategoryEntity;
use App\Modules\Product\Models\ProductCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ProductCategoryRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 ProductCategoryEntity
     */
    public function saveEntity(ProductCategoryEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ProductCategoryEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return ProductCategoryEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ProductCategoryEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return ProductCategoryEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('product_categories');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ProductCategory;
    }
}
