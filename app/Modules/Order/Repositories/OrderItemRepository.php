<?php

declare(strict_types=1);

namespace App\Modules\Order\Repositories;

use App\Modules\Order\Entities\OrderItemEntity;
use App\Modules\Order\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class OrderItemRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 OrderItemEntity
     */
    public function saveEntity(OrderItemEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?OrderItemEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return OrderItemEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?OrderItemEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return OrderItemEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('order_items');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new OrderItem;
    }
}
