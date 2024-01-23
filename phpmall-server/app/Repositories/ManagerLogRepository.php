<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\ManagerLogEntity;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class ManagerLogRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ManagerLogRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ManagerLogRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ManagerLogRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ManagerLogEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ManagerLogEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new ManagerLogEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ManagerLogEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new ManagerLogEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('manager_logs');
    }
}
