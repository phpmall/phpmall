<?php

declare(strict_types=1);

namespace App\Modules\User\Repositories;

use App\Modules\User\Entities\UserEntity;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class UserRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 UserEntity
     */
    public function saveEntity(UserEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?UserEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return UserEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?UserEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return UserEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('users');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new User;
    }
}
