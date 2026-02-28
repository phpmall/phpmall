<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Repositories;

use App\Bundles\Activity\Entities\ActivityWholesaleEntity;
use App\Bundles\Activity\Models\ActivityWholesale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ActivityWholesaleRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ActivityWholesaleRepository $instance = null;

    /**
     * 单例 ActivityWholesaleRepository
     */
    public static function getInstance(): ActivityWholesaleRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ActivityWholesaleRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ActivityWholesaleEntity
     */
    public function saveEntity(ActivityWholesaleEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ActivityWholesaleEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ActivityWholesaleEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ActivityWholesaleEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ActivityWholesaleEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('activity_wholesale');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ActivityWholesale;
    }
}
