<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\SystemEmployeePermissionEntity;
use App\Models\SystemEmployeePermission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class SystemEmployeePermissionRepository extends CurdRepository implements RepositoryInterface
{
    private static ?SystemEmployeePermissionRepository $instance = null;

    /**
     * 单例 SystemEmployeePermissionRepository
     */
    public static function getInstance(): SystemEmployeePermissionRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new SystemEmployeePermissionRepository();
        }

        return self::$instance;
    }

    /**
     * 添加 SystemEmployeePermissionEntity
     */
    public function saveEntity(SystemEmployeePermissionEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SystemEmployeePermissionEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new SystemEmployeePermissionEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SystemEmployeePermissionEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new SystemEmployeePermissionEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('system_employee_permissions');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new SystemEmployeePermission();
    }
}
