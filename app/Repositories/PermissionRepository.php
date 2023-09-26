<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\PermissionEntity;
use App\Models\Permission;
use Focite\Generator\Contracts\RepositoryInterface;
use Focite\Generator\Repositories\CurdRepository;

class PermissionRepository extends CurdRepository implements RepositoryInterface
{
    private static ?PermissionRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): PermissionRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new PermissionRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(PermissionEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?PermissionEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new PermissionEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?PermissionEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new PermissionEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): Permission
    {
        return new Permission();
    }
}
