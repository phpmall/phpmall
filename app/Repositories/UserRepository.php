<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\UserEntity;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class UserRepository extends CurdRepository implements RepositoryInterface
{
    private static ?UserRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): UserRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(UserEntity $entity): int
    {
        return $this->save($entity->toArray());
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

        $entity = new UserEntity();
        $entity->setData($data);

        return $entity;
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

        $entity = new UserEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('users');
    }
}
