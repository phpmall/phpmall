<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Repositories;

use App\Bundles\Shop\Entities\ShopAutoManageEntity;
use App\Bundles\Shop\Models\ShopAutoManage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ShopAutoManageRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ShopAutoManageRepository $instance = null;

    /**
     * 单例 ShopAutoManageRepository
     */
    public static function getInstance(): ShopAutoManageRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ShopAutoManageRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ShopAutoManageEntity
     */
    public function saveEntity(ShopAutoManageEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ShopAutoManageEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ShopAutoManageEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ShopAutoManageEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ShopAutoManageEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('shop_auto_manage');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ShopAutoManage;
    }
}
