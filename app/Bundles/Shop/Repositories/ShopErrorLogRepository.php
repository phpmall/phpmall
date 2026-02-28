<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Repositories;

use App\Bundles\Shop\Entities\ShopErrorLogEntity;
use App\Bundles\Shop\Models\ShopErrorLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ShopErrorLogRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ShopErrorLogRepository $instance = null;

    /**
     * 单例 ShopErrorLogRepository
     */
    public static function getInstance(): ShopErrorLogRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ShopErrorLogRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ShopErrorLogEntity
     */
    public function saveEntity(ShopErrorLogEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ShopErrorLogEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ShopErrorLogEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ShopErrorLogEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ShopErrorLogEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('shop_error_log');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ShopErrorLog;
    }
}
