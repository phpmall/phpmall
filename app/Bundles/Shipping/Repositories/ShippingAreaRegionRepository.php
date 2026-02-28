<?php

declare(strict_types=1);

namespace App\Bundles\Shipping\Repositories;

use App\Bundles\Shipping\Entities\ShippingAreaRegionEntity;
use App\Bundles\Shipping\Models\ShippingAreaRegion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ShippingAreaRegionRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ShippingAreaRegionRepository $instance = null;

    /**
     * 单例 ShippingAreaRegionRepository
     */
    public static function getInstance(): ShippingAreaRegionRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ShippingAreaRegionRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ShippingAreaRegionEntity
     */
    public function saveEntity(ShippingAreaRegionEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ShippingAreaRegionEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ShippingAreaRegionEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ShippingAreaRegionEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ShippingAreaRegionEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('shipping_area_region');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ShippingAreaRegion;
    }
}
