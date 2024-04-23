<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\SystemEmployeeRoleEntity;
use App\Models\SystemEmployeeRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class SystemEmployeeRoleRepository extends CurdRepository implements RepositoryInterface
{
    private static ?SystemEmployeeRoleRepository $instance = null;

    /**
     * 单例 SystemEmployeeRoleRepository
     */
    public static function getInstance(): SystemEmployeeRoleRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new SystemEmployeeRoleRepository();
        }

        return self::$instance;
    }

    /**
     * 添加 SystemEmployeeRoleEntity
     */
    public function saveEntity(SystemEmployeeRoleEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SystemEmployeeRoleEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new SystemEmployeeRoleEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SystemEmployeeRoleEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new SystemEmployeeRoleEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('system_employee_roles');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new SystemEmployeeRole();
    }
}
