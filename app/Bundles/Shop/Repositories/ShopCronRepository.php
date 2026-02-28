<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Repositories;

use App\Bundles\Shop\Entities\ShopCronEntity;
use App\Bundles\Shop\Models\ShopCron;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ShopCronRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ShopCronRepository $instance = null;

    /**
     * 单例 ShopCronRepository
     */
    public static function getInstance(): ShopCronRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ShopCronRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ShopCronEntity
     */
    public function saveEntity(ShopCronEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ShopCronEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ShopCronEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ShopCronEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ShopCronEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('shop_cron');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ShopCron;
    }
}
