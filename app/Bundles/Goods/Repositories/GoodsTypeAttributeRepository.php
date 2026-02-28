<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Repositories;

use App\Bundles\Goods\Entities\GoodsTypeAttributeEntity;
use App\Bundles\Goods\Models\GoodsTypeAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class GoodsTypeAttributeRepository extends CurdRepository implements RepositoryInterface
{
    private static ?GoodsTypeAttributeRepository $instance = null;

    /**
     * 单例 GoodsTypeAttributeRepository
     */
    public static function getInstance(): GoodsTypeAttributeRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new GoodsTypeAttributeRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 GoodsTypeAttributeEntity
     */
    public function saveEntity(GoodsTypeAttributeEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?GoodsTypeAttributeEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new GoodsTypeAttributeEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?GoodsTypeAttributeEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new GoodsTypeAttributeEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('goods_type_attribute');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new GoodsTypeAttribute;
    }
}
