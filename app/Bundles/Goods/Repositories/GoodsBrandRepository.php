<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Repositories;

use App\Bundles\Goods\Entities\GoodsBrandEntity;
use App\Bundles\Goods\Models\GoodsBrand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class GoodsBrandRepository extends CurdRepository implements RepositoryInterface
{
    private static ?GoodsBrandRepository $instance = null;

    /**
     * 单例 GoodsBrandRepository
     */
    public static function getInstance(): GoodsBrandRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new GoodsBrandRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 GoodsBrandEntity
     */
    public function saveEntity(GoodsBrandEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?GoodsBrandEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new GoodsBrandEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?GoodsBrandEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new GoodsBrandEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('goods_brand');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new GoodsBrand;
    }
}
