<?php

declare(strict_types=1);

namespace App\Domains\Order\Repositories;

use App\Domains\Order\Entities\OrderShipmentEntity;
use App\Domains\Order\Models\OrderShipment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class OrderShipmentRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 OrderShipmentEntity
     */
    public function saveEntity(OrderShipmentEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?OrderShipmentEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return OrderShipmentEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?OrderShipmentEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return OrderShipmentEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('order_shipments');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new OrderShipment;
    }
}
