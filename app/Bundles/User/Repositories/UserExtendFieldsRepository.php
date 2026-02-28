<?php

declare(strict_types=1);

namespace App\Bundles\User\Repositories;

use App\Bundles\User\Entities\UserExtendFieldsEntity;
use App\Bundles\User\Models\UserExtendFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class UserExtendFieldsRepository extends CurdRepository implements RepositoryInterface
{
    private static ?UserExtendFieldsRepository $instance = null;

    /**
     * 单例 UserExtendFieldsRepository
     */
    public static function getInstance(): UserExtendFieldsRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserExtendFieldsRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 UserExtendFieldsEntity
     */
    public function saveEntity(UserExtendFieldsEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?UserExtendFieldsEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new UserExtendFieldsEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?UserExtendFieldsEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new UserExtendFieldsEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('user_extend_fields');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new UserExtendFields;
    }
}
