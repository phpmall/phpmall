<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\ShippingEntity;
use App\Models\Shipping;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class ShippingRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ShippingRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ShippingRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ShippingRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ShippingEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ShippingEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new ShippingEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ShippingEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new ShippingEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): Shipping
    {
        return new Shipping();
    }
}
