<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Repositories;

use App\Bundles\Shop\Entities\ShopNavEntity;
use App\Bundles\Shop\Models\ShopNav;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ShopNavRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ShopNavRepository $instance = null;

    /**
     * 单例 ShopNavRepository
     */
    public static function getInstance(): ShopNavRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ShopNavRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ShopNavEntity
     */
    public function saveEntity(ShopNavEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ShopNavEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ShopNavEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ShopNavEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ShopNavEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('shop_nav');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ShopNav;
    }
}
