<?php

declare(strict_types=1);

namespace App\Modules\Order\Repositories;

use App\Modules\Order\Entities\OrderRefundEntity;
use App\Modules\Order\Models\OrderRefund;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class OrderRefundRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 OrderRefundEntity
     */
    public function saveEntity(OrderRefundEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?OrderRefundEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return OrderRefundEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?OrderRefundEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return OrderRefundEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('order_refunds');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new OrderRefund;
    }
}
