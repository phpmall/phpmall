<?php

declare(strict_types=1);

namespace App\Bundles\Order\Repositories;

use App\Bundles\Order\Entities\OrderDeliveryOrderEntity;
use App\Bundles\Order\Models\OrderDeliveryOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class OrderDeliveryOrderRepository extends CurdRepository implements RepositoryInterface
{
    private static ?OrderDeliveryOrderRepository $instance = null;

    /**
     * 单例 OrderDeliveryOrderRepository
     */
    public static function getInstance(): OrderDeliveryOrderRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new OrderDeliveryOrderRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 OrderDeliveryOrderEntity
     */
    public function saveEntity(OrderDeliveryOrderEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?OrderDeliveryOrderEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new OrderDeliveryOrderEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?OrderDeliveryOrderEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new OrderDeliveryOrderEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('order_delivery_order');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new OrderDeliveryOrder;
    }
}
