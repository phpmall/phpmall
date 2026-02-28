<?php

declare(strict_types=1);

namespace App\Bundles\User\Repositories;

use App\Bundles\User\Entities\UserBonusEntity;
use App\Bundles\User\Models\UserBonus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class UserBonusRepository extends CurdRepository implements RepositoryInterface
{
    private static ?UserBonusRepository $instance = null;

    /**
     * 单例 UserBonusRepository
     */
    public static function getInstance(): UserBonusRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserBonusRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 UserBonusEntity
     */
    public function saveEntity(UserBonusEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?UserBonusEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new UserBonusEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?UserBonusEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new UserBonusEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('user_bonus');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new UserBonus;
    }
}
