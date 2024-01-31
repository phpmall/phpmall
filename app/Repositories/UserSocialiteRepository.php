<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\UserSocialiteEntity;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class UserSocialiteRepository extends CurdRepository implements RepositoryInterface
{
    private static ?UserSocialiteRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): UserSocialiteRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserSocialiteRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(UserSocialiteEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?UserSocialiteEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new UserSocialiteEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?UserSocialiteEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new UserSocialiteEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('user_socialites');
    }
}
