<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\UserAddressEntity;
use App\Models\UserAddress;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class UserAddressRepository extends CurdRepository implements RepositoryInterface
{
    private static ?UserAddressRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): UserAddressRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserAddressRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(UserAddressEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?UserAddressEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new UserAddressEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?UserAddressEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new UserAddressEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): UserAddress
    {
        return new UserAddress();
    }
}
