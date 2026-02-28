<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Repositories;

use App\Bundles\Activity\Entities\ActivityEntity;
use App\Bundles\Activity\Models\Activity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ActivityRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ActivityRepository $instance = null;

    /**
     * 单例 ActivityRepository
     */
    public static function getInstance(): ActivityRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ActivityRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ActivityEntity
     */
    public function saveEntity(ActivityEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ActivityEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ActivityEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ActivityEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ActivityEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('activity');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new Activity;
    }
}
