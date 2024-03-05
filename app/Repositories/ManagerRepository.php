<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\ManagerEntity;
use App\Contracts\RepositoryInterface;
use App\Repositories\CurdRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class ManagerRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ManagerRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ManagerRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ManagerRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ManagerEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ManagerEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new ManagerEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ManagerEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new ManagerEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('managers');
    }
}
