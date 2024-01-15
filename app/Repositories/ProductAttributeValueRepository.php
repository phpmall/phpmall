<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\ProductAttributeValueEntity;
use App\Models\ProductAttributeValue;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class ProductAttributeValueRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ProductAttributeValueRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ProductAttributeValueRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ProductAttributeValueRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ProductAttributeValueEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ProductAttributeValueEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new ProductAttributeValueEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ProductAttributeValueEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new ProductAttributeValueEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): ProductAttributeValue
    {
        return new ProductAttributeValue();
    }
}
