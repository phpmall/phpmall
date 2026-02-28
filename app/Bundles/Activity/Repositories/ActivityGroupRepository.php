<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Repositories;

use App\Bundles\Activity\Entities\ActivityGroupEntity;
use App\Bundles\Activity\Models\ActivityGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ActivityGroupRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ActivityGroupRepository $instance = null;

    /**
     * 单例 ActivityGroupRepository
     */
    public static function getInstance(): ActivityGroupRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ActivityGroupRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ActivityGroupEntity
     */
    public function saveEntity(ActivityGroupEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ActivityGroupEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ActivityGroupEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ActivityGroupEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ActivityGroupEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('activity_group');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ActivityGroup;
    }
}
