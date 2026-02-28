<?php

declare(strict_types=1);

namespace App\Bundles\Order\Repositories;

use App\Bundles\Order\Entities\OrderBackGoodsEntity;
use App\Bundles\Order\Models\OrderBackGoods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class OrderBackGoodsRepository extends CurdRepository implements RepositoryInterface
{
    private static ?OrderBackGoodsRepository $instance = null;

    /**
     * 单例 OrderBackGoodsRepository
     */
    public static function getInstance(): OrderBackGoodsRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new OrderBackGoodsRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 OrderBackGoodsEntity
     */
    public function saveEntity(OrderBackGoodsEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?OrderBackGoodsEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new OrderBackGoodsEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?OrderBackGoodsEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new OrderBackGoodsEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('order_back_goods');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new OrderBackGoods;
    }
}
