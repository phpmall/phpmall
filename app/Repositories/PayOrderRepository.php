<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\PayOrderEntity;
use App\Models\PayOrder;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class PayOrderRepository extends CurdRepository implements RepositoryInterface
{
    private static ?PayOrderRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): PayOrderRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new PayOrderRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(PayOrderEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?PayOrderEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new PayOrderEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?PayOrderEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new PayOrderEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): PayOrder
    {
        return new PayOrder();
    }
}
