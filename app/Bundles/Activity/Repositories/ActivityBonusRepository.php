<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Repositories;

use App\Bundles\Activity\Entities\ActivityBonusEntity;
use App\Bundles\Activity\Models\ActivityBonus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ActivityBonusRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ActivityBonusRepository $instance = null;

    /**
     * 单例 ActivityBonusRepository
     */
    public static function getInstance(): ActivityBonusRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ActivityBonusRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ActivityBonusEntity
     */
    public function saveEntity(ActivityBonusEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ActivityBonusEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ActivityBonusEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ActivityBonusEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ActivityBonusEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('activity_bonus');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ActivityBonus;
    }
}
