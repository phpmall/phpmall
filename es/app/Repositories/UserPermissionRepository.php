<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\UserPermissionEntity;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class UserPermissionRepository extends CurdRepository implements RepositoryInterface
{
    private static ?UserPermissionRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): UserPermissionRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserPermissionRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(UserPermissionEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?UserPermissionEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new UserPermissionEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?UserPermissionEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new UserPermissionEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('user_permissions');
    }
}
