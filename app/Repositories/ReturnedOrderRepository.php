<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\ReturnedOrderEntity;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class ReturnedOrderRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ReturnedOrderRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ReturnedOrderRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ReturnedOrderRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ReturnedOrderEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ReturnedOrderEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new ReturnedOrderEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ReturnedOrderEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new ReturnedOrderEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('returned_order');
    }
}
