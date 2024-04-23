<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\SystemRoleEntity;
use App\Models\SystemRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class SystemRoleRepository extends CurdRepository implements RepositoryInterface
{
    private static ?SystemRoleRepository $instance = null;

    /**
     * 单例 SystemRoleRepository
     */
    public static function getInstance(): SystemRoleRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new SystemRoleRepository();
        }

        return self::$instance;
    }

    /**
     * 添加 SystemRoleEntity
     */
    public function saveEntity(SystemRoleEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SystemRoleEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new SystemRoleEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SystemRoleEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new SystemRoleEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('system_roles');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new SystemRole();
    }
}
