<?php

declare(strict_types=1);

namespace App\Bundles\User\Repositories;

use App\Bundles\User\Entities\UserCollectEntity;
use App\Bundles\User\Models\UserCollect;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class UserCollectRepository extends CurdRepository implements RepositoryInterface
{
    private static ?UserCollectRepository $instance = null;

    /**
     * 单例 UserCollectRepository
     */
    public static function getInstance(): UserCollectRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserCollectRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 UserCollectEntity
     */
    public function saveEntity(UserCollectEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?UserCollectEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new UserCollectEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?UserCollectEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new UserCollectEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('user_collect');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new UserCollect;
    }
}
