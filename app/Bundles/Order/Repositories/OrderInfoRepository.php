<?php

declare(strict_types=1);

namespace App\Bundles\Order\Repositories;

use App\Bundles\Order\Entities\OrderInfoEntity;
use App\Bundles\Order\Models\OrderInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class OrderInfoRepository extends CurdRepository implements RepositoryInterface
{
    private static ?OrderInfoRepository $instance = null;

    /**
     * 单例 OrderInfoRepository
     */
    public static function getInstance(): OrderInfoRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new OrderInfoRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 OrderInfoEntity
     */
    public function saveEntity(OrderInfoEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?OrderInfoEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new OrderInfoEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?OrderInfoEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new OrderInfoEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('order_info');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new OrderInfo;
    }
}
