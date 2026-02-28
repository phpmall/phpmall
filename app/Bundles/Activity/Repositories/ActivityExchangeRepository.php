<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Repositories;

use App\Bundles\Activity\Entities\ActivityExchangeEntity;
use App\Bundles\Activity\Models\ActivityExchange;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ActivityExchangeRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ActivityExchangeRepository $instance = null;

    /**
     * 单例 ActivityExchangeRepository
     */
    public static function getInstance(): ActivityExchangeRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ActivityExchangeRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ActivityExchangeEntity
     */
    public function saveEntity(ActivityExchangeEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ActivityExchangeEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ActivityExchangeEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ActivityExchangeEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ActivityExchangeEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('activity_exchange');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ActivityExchange;
    }
}
