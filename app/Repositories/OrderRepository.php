<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\OrderEntity;
use App\Models\Order;
use Focite\Generator\Contracts\RepositoryInterface;
use Focite\Generator\Repositories\CurdRepository;

class OrderRepository extends CurdRepository implements RepositoryInterface
{
    private static ?OrderRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): OrderRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new OrderRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(OrderEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?OrderEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new OrderEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?OrderEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new OrderEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): Order
    {
        return new Order();
    }
}
