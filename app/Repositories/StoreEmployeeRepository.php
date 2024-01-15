<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\StoreEmployeeEntity;
use App\Models\StoreEmployee;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class StoreEmployeeRepository extends CurdRepository implements RepositoryInterface
{
    private static ?StoreEmployeeRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): StoreEmployeeRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new StoreEmployeeRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(StoreEmployeeEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?StoreEmployeeEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new StoreEmployeeEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?StoreEmployeeEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new StoreEmployeeEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): StoreEmployee
    {
        return new StoreEmployee();
    }
}
