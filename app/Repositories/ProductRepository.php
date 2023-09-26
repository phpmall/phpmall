<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\ProductEntity;
use App\Models\Product;
use Focite\Generator\Contracts\RepositoryInterface;
use Focite\Generator\Repositories\CurdRepository;

class ProductRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ProductRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ProductRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ProductRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ProductEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ProductEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new ProductEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ProductEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new ProductEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): Product
    {
        return new Product();
    }
}
