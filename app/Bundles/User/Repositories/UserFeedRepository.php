<?php

declare(strict_types=1);

namespace App\Bundles\User\Repositories;

use App\Bundles\User\Entities\UserFeedEntity;
use App\Bundles\User\Models\UserFeed;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class UserFeedRepository extends CurdRepository implements RepositoryInterface
{
    private static ?UserFeedRepository $instance = null;

    /**
     * 单例 UserFeedRepository
     */
    public static function getInstance(): UserFeedRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserFeedRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 UserFeedEntity
     */
    public function saveEntity(UserFeedEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?UserFeedEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new UserFeedEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?UserFeedEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new UserFeedEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('user_feed');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new UserFeed;
    }
}
