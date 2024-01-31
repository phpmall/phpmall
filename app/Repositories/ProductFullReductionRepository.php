<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\ProductFullReductionEntity;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class ProductFullReductionRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ProductFullReductionRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ProductFullReductionRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ProductFullReductionRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ProductFullReductionEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ProductFullReductionEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new ProductFullReductionEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ProductFullReductionEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new ProductFullReductionEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('product_full_reductions');
    }
}
