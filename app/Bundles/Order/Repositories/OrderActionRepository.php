<?php

declare(strict_types=1);

namespace App\Bundles\Order\Repositories;

use App\Bundles\Order\Entities\OrderActionEntity;
use App\Bundles\Order\Models\OrderAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class OrderActionRepository extends CurdRepository implements RepositoryInterface
{
    private static ?OrderActionRepository $instance = null;

    /**
     * 单例 OrderActionRepository
     */
    public static function getInstance(): OrderActionRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new OrderActionRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 OrderActionEntity
     */
    public function saveEntity(OrderActionEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?OrderActionEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new OrderActionEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?OrderActionEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new OrderActionEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('order_action');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new OrderAction;
    }
}
