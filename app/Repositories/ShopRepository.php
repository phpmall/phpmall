<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\ShopEntity;
use App\Models\Shop;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class ShopRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ShopRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ShopRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ShopRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ShopEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ShopEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new ShopEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ShopEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new ShopEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): Shop
    {
        return new Shop();
    }
}
