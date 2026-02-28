<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Repositories;

use App\Bundles\Goods\Entities\GoodsVirtualCardEntity;
use App\Bundles\Goods\Models\GoodsVirtualCard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class GoodsVirtualCardRepository extends CurdRepository implements RepositoryInterface
{
    private static ?GoodsVirtualCardRepository $instance = null;

    /**
     * 单例 GoodsVirtualCardRepository
     */
    public static function getInstance(): GoodsVirtualCardRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new GoodsVirtualCardRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 GoodsVirtualCardEntity
     */
    public function saveEntity(GoodsVirtualCardEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?GoodsVirtualCardEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new GoodsVirtualCardEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?GoodsVirtualCardEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new GoodsVirtualCardEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('goods_virtual_card');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new GoodsVirtualCard;
    }
}
