<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Repositories;

use App\Bundles\Goods\Entities\GoodsCatEntity;
use App\Bundles\Goods\Models\GoodsCat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class GoodsCatRepository extends CurdRepository implements RepositoryInterface
{
    private static ?GoodsCatRepository $instance = null;

    /**
     * 单例 GoodsCatRepository
     */
    public static function getInstance(): GoodsCatRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new GoodsCatRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 GoodsCatEntity
     */
    public function saveEntity(GoodsCatEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?GoodsCatEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new GoodsCatEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?GoodsCatEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new GoodsCatEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('goods_cat');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new GoodsCat;
    }
}
