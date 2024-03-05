<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\OrderLogEntity;
use App\Contracts\RepositoryInterface;
use App\Repositories\CurdRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class OrderLogRepository extends CurdRepository implements RepositoryInterface
{
    private static ?OrderLogRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): OrderLogRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new OrderLogRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(OrderLogEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?OrderLogEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new OrderLogEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?OrderLogEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new OrderLogEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('order_logs');
    }
}
