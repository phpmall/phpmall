<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Repositories;

use App\Bundles\Goods\Entities\GoodsMemberPriceEntity;
use App\Bundles\Goods\Models\GoodsMemberPrice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class GoodsMemberPriceRepository extends CurdRepository implements RepositoryInterface
{
    private static ?GoodsMemberPriceRepository $instance = null;

    /**
     * 单例 GoodsMemberPriceRepository
     */
    public static function getInstance(): GoodsMemberPriceRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new GoodsMemberPriceRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 GoodsMemberPriceEntity
     */
    public function saveEntity(GoodsMemberPriceEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?GoodsMemberPriceEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new GoodsMemberPriceEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?GoodsMemberPriceEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new GoodsMemberPriceEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('goods_member_price');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new GoodsMemberPrice;
    }
}
