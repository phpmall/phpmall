<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Repositories;

use App\Bundles\Activity\Entities\ActivityTopicEntity;
use App\Bundles\Activity\Models\ActivityTopic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ActivityTopicRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ActivityTopicRepository $instance = null;

    /**
     * 单例 ActivityTopicRepository
     */
    public static function getInstance(): ActivityTopicRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ActivityTopicRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ActivityTopicEntity
     */
    public function saveEntity(ActivityTopicEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ActivityTopicEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ActivityTopicEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ActivityTopicEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ActivityTopicEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('activity_topic');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ActivityTopic;
    }
}
