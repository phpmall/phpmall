<?php

declare(strict_types=1);

namespace App\Bundles\Order\Repositories;

use App\Bundles\Order\Entities\OrderPayEntity;
use App\Bundles\Order\Models\OrderPay;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class OrderPayRepository extends CurdRepository implements RepositoryInterface
{
    private static ?OrderPayRepository $instance = null;

    /**
     * 单例 OrderPayRepository
     */
    public static function getInstance(): OrderPayRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new OrderPayRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 OrderPayEntity
     */
    public function saveEntity(OrderPayEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?OrderPayEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new OrderPayEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?OrderPayEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new OrderPayEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('order_pay');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new OrderPay;
    }
}
