<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Repositories;

use App\Bundles\Activity\Entities\ActivitySnatchEntity;
use App\Bundles\Activity\Models\ActivitySnatch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ActivitySnatchRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ActivitySnatchRepository $instance = null;

    /**
     * 单例 ActivitySnatchRepository
     */
    public static function getInstance(): ActivitySnatchRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ActivitySnatchRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ActivitySnatchEntity
     */
    public function saveEntity(ActivitySnatchEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ActivitySnatchEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ActivitySnatchEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ActivitySnatchEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ActivitySnatchEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('activity_snatch');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ActivitySnatch;
    }
}
