<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\SystemEmployeeEntity;
use App\Models\SystemEmployee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class SystemEmployeeRepository extends CurdRepository implements RepositoryInterface
{
    private static ?SystemEmployeeRepository $instance = null;

    /**
     * 单例 SystemEmployeeRepository
     */
    public static function getInstance(): SystemEmployeeRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new SystemEmployeeRepository();
        }

        return self::$instance;
    }

    /**
     * 添加 SystemEmployeeEntity
     */
    public function saveEntity(SystemEmployeeEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SystemEmployeeEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new SystemEmployeeEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SystemEmployeeEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new SystemEmployeeEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('system_employees');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new SystemEmployee();
    }
}
