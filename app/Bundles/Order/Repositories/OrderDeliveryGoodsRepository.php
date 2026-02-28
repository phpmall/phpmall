<?php

declare(strict_types=1);

namespace App\Bundles\Order\Repositories;

use App\Bundles\Order\Entities\OrderDeliveryGoodsEntity;
use App\Bundles\Order\Models\OrderDeliveryGoods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class OrderDeliveryGoodsRepository extends CurdRepository implements RepositoryInterface
{
    private static ?OrderDeliveryGoodsRepository $instance = null;

    /**
     * 单例 OrderDeliveryGoodsRepository
     */
    public static function getInstance(): OrderDeliveryGoodsRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new OrderDeliveryGoodsRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 OrderDeliveryGoodsEntity
     */
    public function saveEntity(OrderDeliveryGoodsEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?OrderDeliveryGoodsEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new OrderDeliveryGoodsEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?OrderDeliveryGoodsEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new OrderDeliveryGoodsEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('order_delivery_goods');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new OrderDeliveryGoods;
    }
}
