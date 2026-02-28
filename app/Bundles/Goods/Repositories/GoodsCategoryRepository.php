<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Repositories;

use App\Bundles\Goods\Entities\GoodsCategoryEntity;
use App\Bundles\Goods\Models\GoodsCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class GoodsCategoryRepository extends CurdRepository implements RepositoryInterface
{
    private static ?GoodsCategoryRepository $instance = null;

    /**
     * 单例 GoodsCategoryRepository
     */
    public static function getInstance(): GoodsCategoryRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new GoodsCategoryRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 GoodsCategoryEntity
     */
    public function saveEntity(GoodsCategoryEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?GoodsCategoryEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new GoodsCategoryEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?GoodsCategoryEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new GoodsCategoryEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('goods_category');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new GoodsCategory;
    }
}
