<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\SystemRolePermissionEntity;
use App\Models\SystemRolePermission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class SystemRolePermissionRepository extends CurdRepository implements RepositoryInterface
{
    private static ?SystemRolePermissionRepository $instance = null;

    /**
     * 单例 SystemRolePermissionRepository
     */
    public static function getInstance(): SystemRolePermissionRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new SystemRolePermissionRepository();
        }

        return self::$instance;
    }

    /**
     * 添加 SystemRolePermissionEntity
     */
    public function saveEntity(SystemRolePermissionEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SystemRolePermissionEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new SystemRolePermissionEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SystemRolePermissionEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new SystemRolePermissionEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('system_role_permissions');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new SystemRolePermission();
    }
}
