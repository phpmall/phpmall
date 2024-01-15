<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\CategoryProductAttribute;
use App\Models\Entity\CategoryProductAttributeEntity;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class CategoryProductAttributeRepository extends CurdRepository implements RepositoryInterface
{
    private static ?CategoryProductAttributeRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): CategoryProductAttributeRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new CategoryProductAttributeRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(CategoryProductAttributeEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?CategoryProductAttributeEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new CategoryProductAttributeEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?CategoryProductAttributeEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new CategoryProductAttributeEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): CategoryProductAttribute
    {
        return new CategoryProductAttribute();
    }
}
