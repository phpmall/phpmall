<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Repositories;

use App\Bundles\Shop\Entities\ShopConfigEntity;
use App\Bundles\Shop\Models\ShopConfig;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ShopConfigRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ShopConfigRepository $instance = null;

    /**
     * 单例 ShopConfigRepository
     */
    public static function getInstance(): ShopConfigRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ShopConfigRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ShopConfigEntity
     */
    public function saveEntity(ShopConfigEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ShopConfigEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ShopConfigEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ShopConfigEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ShopConfigEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('shop_config');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ShopConfig;
    }
}
