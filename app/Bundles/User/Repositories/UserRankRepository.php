<?php

declare(strict_types=1);

namespace App\Bundles\User\Repositories;

use App\Bundles\User\Entities\UserRankEntity;
use App\Bundles\User\Models\UserRank;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class UserRankRepository extends CurdRepository implements RepositoryInterface
{
    private static ?UserRankRepository $instance = null;

    /**
     * 单例 UserRankRepository
     */
    public static function getInstance(): UserRankRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserRankRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 UserRankEntity
     */
    public function saveEntity(UserRankEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?UserRankEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new UserRankEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?UserRankEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new UserRankEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('user_rank');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new UserRank;
    }
}
