<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\SystemPermissionEntity;
use App\Models\SystemPermission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class SystemPermissionRepository extends CurdRepository implements RepositoryInterface
{
    private static ?SystemPermissionRepository $instance = null;

    /**
     * 单例 SystemPermissionRepository
     */
    public static function getInstance(): SystemPermissionRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new SystemPermissionRepository();
        }

        return self::$instance;
    }

    /**
     * 添加 SystemPermissionEntity
     */
    public function saveEntity(SystemPermissionEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SystemPermissionEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new SystemPermissionEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SystemPermissionEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new SystemPermissionEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('system_permissions');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new SystemPermission();
    }
}
