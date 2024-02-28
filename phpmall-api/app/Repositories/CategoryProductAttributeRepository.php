<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\CategoryProductAttributeEntity;
use App\Foundation\Contracts\RepositoryInterface;
use App\Foundation\Repositories\CurdRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class CategoryProductAttributeRepository extends CurdRepository implements RepositoryInterface
{
    private static ?CategoryProductAttributeRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): CategoryProductAttributeRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new CategoryProductAttributeRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(CategoryProductAttributeEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?CategoryProductAttributeEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new CategoryProductAttributeEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?CategoryProductAttributeEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new CategoryProductAttributeEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('category_product_attributes');
    }
}
