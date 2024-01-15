<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Customer;
use App\Models\Entity\CustomerEntity;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class CustomerRepository extends CurdRepository implements RepositoryInterface
{
    private static ?CustomerRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): CustomerRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new CustomerRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(CustomerEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?CustomerEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new CustomerEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?CustomerEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new CustomerEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): Customer
    {
        return new Customer();
    }
}
