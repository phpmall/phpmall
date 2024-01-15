<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\ProductAttributeEntity;
use App\Models\ProductAttribute;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class ProductAttributeRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ProductAttributeRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ProductAttributeRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ProductAttributeRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ProductAttributeEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ProductAttributeEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new ProductAttributeEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ProductAttributeEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new ProductAttributeEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): ProductAttribute
    {
        return new ProductAttribute();
    }
}
