<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\SystemDepartmentEntity;
use App\Models\SystemDepartment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class SystemDepartmentRepository extends CurdRepository implements RepositoryInterface
{
    private static ?SystemDepartmentRepository $instance = null;

    /**
     * 单例 SystemDepartmentRepository
     */
    public static function getInstance(): SystemDepartmentRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new SystemDepartmentRepository();
        }

        return self::$instance;
    }

    /**
     * 添加 SystemDepartmentEntity
     */
    public function saveEntity(SystemDepartmentEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SystemDepartmentEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new SystemDepartmentEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SystemDepartmentEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new SystemDepartmentEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('system_departments');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new SystemDepartment();
    }
}
