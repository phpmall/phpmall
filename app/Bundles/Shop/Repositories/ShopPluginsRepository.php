<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Repositories;

use App\Bundles\Shop\Entities\ShopPluginsEntity;
use App\Bundles\Shop\Models\ShopPlugins;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ShopPluginsRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ShopPluginsRepository $instance = null;

    /**
     * 单例 ShopPluginsRepository
     */
    public static function getInstance(): ShopPluginsRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ShopPluginsRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ShopPluginsEntity
     */
    public function saveEntity(ShopPluginsEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ShopPluginsEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ShopPluginsEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ShopPluginsEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ShopPluginsEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('shop_plugins');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ShopPlugins;
    }
}
