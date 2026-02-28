<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Repositories;

use App\Bundles\Goods\Entities\GoodsCatRecommendEntity;
use App\Bundles\Goods\Models\GoodsCatRecommend;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class GoodsCatRecommendRepository extends CurdRepository implements RepositoryInterface
{
    private static ?GoodsCatRecommendRepository $instance = null;

    /**
     * 单例 GoodsCatRecommendRepository
     */
    public static function getInstance(): GoodsCatRecommendRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new GoodsCatRecommendRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 GoodsCatRecommendEntity
     */
    public function saveEntity(GoodsCatRecommendEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?GoodsCatRecommendEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new GoodsCatRecommendEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?GoodsCatRecommendEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new GoodsCatRecommendEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('goods_cat_recommend');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new GoodsCatRecommend;
    }
}
