<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\RoleEntity;
use App\Contracts\RepositoryInterface;
use App\Repositories\CurdRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class RoleRepository extends CurdRepository implements RepositoryInterface
{
    private static ?RoleRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): RoleRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new RoleRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(RoleEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?RoleEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new RoleEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?RoleEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new RoleEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('roles');
    }
}
