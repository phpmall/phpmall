<?php

declare(strict_types=1);

namespace App\Bundles\Shipping\Repositories;

use App\Bundles\Shipping\Entities\ShippingEntity;
use App\Bundles\Shipping\Models\Shipping;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ShippingRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ShippingRepository $instance = null;

    /**
     * 单例 ShippingRepository
     */
    public static function getInstance(): ShippingRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ShippingRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ShippingEntity
     */
    public function saveEntity(ShippingEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ShippingEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ShippingEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ShippingEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ShippingEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('shipping');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new Shipping;
    }
}
