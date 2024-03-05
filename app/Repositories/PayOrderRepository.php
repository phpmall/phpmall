<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Entities\PayOrderEntity;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class PayOrderRepository extends CurdRepository implements RepositoryInterface
{
    private static ?PayOrderRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): PayOrderRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new PayOrderRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(PayOrderEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?PayOrderEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new PayOrderEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?PayOrderEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new PayOrderEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('pay_orders');
    }
}
