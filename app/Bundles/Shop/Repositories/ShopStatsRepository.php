<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Repositories;

use App\Bundles\Shop\Entities\ShopStatsEntity;
use App\Bundles\Shop\Models\ShopStats;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ShopStatsRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ShopStatsRepository $instance = null;

    /**
     * 单例 ShopStatsRepository
     */
    public static function getInstance(): ShopStatsRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ShopStatsRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ShopStatsEntity
     */
    public function saveEntity(ShopStatsEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ShopStatsEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ShopStatsEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ShopStatsEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ShopStatsEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('shop_stats');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ShopStats;
    }
}
