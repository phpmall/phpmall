<?php

declare(strict_types=1);

namespace App\Bundles\User\Repositories;

use App\Bundles\User\Entities\UserTagEntity;
use App\Bundles\User\Models\UserTag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class UserTagRepository extends CurdRepository implements RepositoryInterface
{
    private static ?UserTagRepository $instance = null;

    /**
     * 单例 UserTagRepository
     */
    public static function getInstance(): UserTagRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserTagRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 UserTagEntity
     */
    public function saveEntity(UserTagEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?UserTagEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new UserTagEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?UserTagEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new UserTagEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('user_tag');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new UserTag;
    }
}
