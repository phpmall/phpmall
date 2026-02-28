<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Repositories;

use App\Bundles\Shop\Entities\ShopRegionEntity;
use App\Bundles\Shop\Models\ShopRegion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ShopRegionRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ShopRegionRepository $instance = null;

    /**
     * 单例 ShopRegionRepository
     */
    public static function getInstance(): ShopRegionRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ShopRegionRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ShopRegionEntity
     */
    public function saveEntity(ShopRegionEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ShopRegionEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ShopRegionEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ShopRegionEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ShopRegionEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('shop_region');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ShopRegion;
    }
}
