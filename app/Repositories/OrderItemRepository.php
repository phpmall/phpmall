<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\OrderItemEntity;
use App\Models\OrderItem;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class OrderItemRepository extends CurdRepository implements RepositoryInterface
{
    private static ?OrderItemRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): OrderItemRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new OrderItemRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(OrderItemEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?OrderItemEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new OrderItemEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?OrderItemEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new OrderItemEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): OrderItem
    {
        return new OrderItem();
    }
}
