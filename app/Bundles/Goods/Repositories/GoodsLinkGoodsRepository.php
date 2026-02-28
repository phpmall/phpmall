<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Repositories;

use App\Bundles\Goods\Entities\GoodsLinkGoodsEntity;
use App\Bundles\Goods\Models\GoodsLinkGoods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class GoodsLinkGoodsRepository extends CurdRepository implements RepositoryInterface
{
    private static ?GoodsLinkGoodsRepository $instance = null;

    /**
     * 单例 GoodsLinkGoodsRepository
     */
    public static function getInstance(): GoodsLinkGoodsRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new GoodsLinkGoodsRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 GoodsLinkGoodsEntity
     */
    public function saveEntity(GoodsLinkGoodsEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?GoodsLinkGoodsEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new GoodsLinkGoodsEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?GoodsLinkGoodsEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new GoodsLinkGoodsEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('goods_link_goods');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new GoodsLinkGoods;
    }
}
