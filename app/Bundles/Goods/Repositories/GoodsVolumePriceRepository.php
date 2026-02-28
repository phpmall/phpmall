<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Repositories;

use App\Bundles\Goods\Entities\GoodsVolumePriceEntity;
use App\Bundles\Goods\Models\GoodsVolumePrice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class GoodsVolumePriceRepository extends CurdRepository implements RepositoryInterface
{
    private static ?GoodsVolumePriceRepository $instance = null;

    /**
     * 单例 GoodsVolumePriceRepository
     */
    public static function getInstance(): GoodsVolumePriceRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new GoodsVolumePriceRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 GoodsVolumePriceEntity
     */
    public function saveEntity(GoodsVolumePriceEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?GoodsVolumePriceEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new GoodsVolumePriceEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?GoodsVolumePriceEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new GoodsVolumePriceEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('goods_volume_price');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new GoodsVolumePrice;
    }
}
