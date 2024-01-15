<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\SellerRoleEntity;
use App\Models\SellerRole;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class SellerRoleRepository extends CurdRepository implements RepositoryInterface
{
    private static ?SellerRoleRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): SellerRoleRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new SellerRoleRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(SellerRoleEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SellerRoleEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new SellerRoleEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SellerRoleEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new SellerRoleEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): SellerRole
    {
        return new SellerRole();
    }
}
