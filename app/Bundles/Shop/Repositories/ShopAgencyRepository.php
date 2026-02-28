<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Repositories;

use App\Bundles\Shop\Entities\ShopAgencyEntity;
use App\Bundles\Shop\Models\ShopAgency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ShopAgencyRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ShopAgencyRepository $instance = null;

    /**
     * 单例 ShopAgencyRepository
     */
    public static function getInstance(): ShopAgencyRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ShopAgencyRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ShopAgencyEntity
     */
    public function saveEntity(ShopAgencyEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ShopAgencyEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ShopAgencyEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ShopAgencyEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ShopAgencyEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('shop_agency');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ShopAgency;
    }
}
