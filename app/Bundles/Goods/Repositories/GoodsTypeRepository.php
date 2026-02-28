<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Repositories;

use App\Bundles\Goods\Entities\GoodsTypeEntity;
use App\Bundles\Goods\Models\GoodsType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class GoodsTypeRepository extends CurdRepository implements RepositoryInterface
{
    private static ?GoodsTypeRepository $instance = null;

    /**
     * 单例 GoodsTypeRepository
     */
    public static function getInstance(): GoodsTypeRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new GoodsTypeRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 GoodsTypeEntity
     */
    public function saveEntity(GoodsTypeEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?GoodsTypeEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new GoodsTypeEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?GoodsTypeEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new GoodsTypeEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('goods_type');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new GoodsType;
    }
}
