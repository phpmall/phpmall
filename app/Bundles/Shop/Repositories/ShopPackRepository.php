<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Repositories;

use App\Bundles\Shop\Entities\ShopPackEntity;
use App\Bundles\Shop\Models\ShopPack;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ShopPackRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ShopPackRepository $instance = null;

    /**
     * 单例 ShopPackRepository
     */
    public static function getInstance(): ShopPackRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ShopPackRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ShopPackEntity
     */
    public function saveEntity(ShopPackEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ShopPackEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ShopPackEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ShopPackEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ShopPackEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('shop_pack');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ShopPack;
    }
}
